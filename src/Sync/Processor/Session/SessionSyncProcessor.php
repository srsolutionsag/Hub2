<?php

namespace srag\Plugins\Hub2\Sync\Processor\Session;

use ilDateTime;
use ilObject2;
use ilObjSession;
use ilRepUtil;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Origin\Config\Session\SessionOriginConfig;
use srag\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;

/**
 * Class SessionSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionSyncProcessor extends ObjectSyncProcessor implements ISessionSyncProcessor
{
    use MetadataSyncProcessor;
    use TaxonomySyncProcessor;

    /**
     * @var SessionProperties
     */
    private $props;
    /**
     * @var SessionOriginConfig
     */
    private $config;
    /**
     * @var array
     */
    protected static $properties
        = [
            "title",
            "description",
            "location",
            "details",
            "name",
            "phone",
            "email",
            "registrationType",
            "registrationMinUsers",
            "registrationMaxUsers",
            "registrationWaitingList",
            "waitingListAutoFill"
        ];
    /**
     * @var \ilTree
     */
    private $tree;
    /**
     * @var \ilRbacAdmin
     */
    private $rbacadmin;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        global $DIC;
        $this->tree = $DIC['tree'];
        $this->rbacadmin = $DIC->rbac()->admin();
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
    }

    /**
     * @return array
     */
    public static function getProperties()
    {
        return self::$properties;
    }

    /**
     * @inheritdoc
     * @param SessionDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjSession = new ilObjSession();
        $ilObjSession->setImportId($this->getImportId($dto));

        // Properties
        foreach (self::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjSession->$setter($dto->$getter());
            }
        }

        /**
         * Dates for first appointment need to be fixed before create since create raises
         * create prepareCalendarAppointments by ilAppEventHandler. At this point the
         * correct dates need to be set, otherwise, the current date will be used.
         **/
        $ilObjSession = $this->setDataForFirstAppointment($dto, $ilObjSession, true);
        $ilObjSession->create();
        $ilObjSession->createReference();
        $a_parent_ref = $this->buildParentRefId($dto);
        $ilObjSession->putInTree($a_parent_ref);
        $ilObjSession->setPermissions($a_parent_ref);
        $this->writeRBACLog($ilObjSession->getRefId());
        /**
         * Since the id is only known after create, it has to be set again before
         * creation of the firs appointment, otherwise no event_appointment will be
         * generated for the session.
         */
        $ilObjSession->getFirstAppointment()->setSessionId($ilObjSession->getId());
        $ilObjSession->getFirstAppointment()->create();
    }

    /**
     * @inheritdoc
     * @param SessionDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjSession = $this->findILIASObject($ilias_id);
        if (!$ilObjSession instanceof \ilObjSession) {
            return;
        }

        foreach (self::getProperties() as $property) {
            if (!$this->props->updateDTOProperty($property)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjSession->$setter($dto->$getter());
            }
        }

        $ilObjSession = $this->setDataForFirstAppointment($dto, $ilObjSession, true);
        $ilObjSession->update();
        $ilObjSession->getFirstAppointment()->update();

        if (!$this->tree->isInTree($ilObjSession->getRefId())) {
            $a_parent_ref = $this->buildParentRefId($dto);
            $ilObjSession->putInTree($a_parent_ref);
        } elseif ($this->props->get(SessionProperties::MOVE_SESSION)) {
            $this->moveSession($ilObjSession, $dto);
        }
    }

    /**
     * @inheritdoc
     * @param SessionDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjSession = $this->findILIASObject($ilias_id);
        if (!$ilObjSession instanceof \ilObjSession) {
            return;
        }

        if ($this->props->get(SessionProperties::DELETE_MODE) == SessionProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(SessionProperties::DELETE_MODE)) {
            case SessionProperties::DELETE_MODE_DELETE:
                $ilObjSession->delete();
                break;
            case SessionProperties::DELETE_MODE_MOVE_TO_TRASH:
                $this->tree->moveToTrash($ilObjSession->getRefId(), true);
                break;
        }
    }

    /**
     * @param int $ilias_id
     * @return ilObjSession|null
     */
    protected function findILIASObject($ilias_id)
    {
        if (!ilObject2::_exists($ilias_id, true)) {
            return null;
        }

        return new ilObjSession($ilias_id);
    }

    /**
     * @throws HubException
     */
    protected function buildParentRefId(SessionDTO $session) : int
    {
        if ($session->getParentIdType() == SessionDTO::PARENT_ID_TYPE_REF_ID && $this->tree->isInTree(
            $session->getParentId()
        )) {
            return (int) $session->getParentId();
        }
        if ($session->getParentIdType() == SessionDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored parent-ID is an external-ID from a category.
            // We must search the parent ref-ID from a category object synced by a linked origin.
            // --> Get an instance of the linked origin and lookup the category by the given external ID.
            $linkedOriginId = $this->config->getLinkedOriginId();
            if ($linkedOriginId === 0) {
                throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
            }
            $originRepository = new OriginRepository();
            $possible_parents = array_merge($originRepository->groups(), $originRepository->courses());
            $arrayFilter = array_filter(
                $possible_parents,
                function ($origin) use ($linkedOriginId) : bool {
                    /** @var IOrigin $origin */
                    return (int) $origin->getId() == $linkedOriginId;
                }
            );
            $origin = array_pop(
                $arrayFilter
            );
            if ($origin === null) {
                $msg = "The linked origin syncing courses or groups was not found, please check that the correct origin is linked";
                throw new HubException($msg);
            }
            $objectFactory = new ObjectFactory($origin);

            if ($origin instanceof ARCourseOrigin) {
                $parent = $objectFactory->course($session->getParentId());
            } else {
                $parent = $objectFactory->group($session->getParentId());
            }

            if (!$parent->getILIASId()) {
                throw new HubException("The linked course or group does not (yet) exist in ILIAS");
            }
            if (!$this->tree->isInTree($parent->getILIASId())) {
                throw new HubException(
                    "Could not find the ref-ID of the parent course or group in the tree: '{$parent->getILIASId()}'"
                );
            }

            return (int) $parent->getILIASId();
        }

        return 0;
    }

    /**
     * @param bool $force
     * @return ilObjSession
     */
    protected function setDataForFirstAppointment(SessionDTO $object, ilObjSession $ilObjSession, $force = false)
    {
        $appointments = $ilObjSession->getAppointments();
        $first = $ilObjSession->getFirstAppointment();
        if ($this->props->updateDTOProperty('start') || $force) {
            $start = new ilDateTime((int) $object->getStart(), IL_CAL_UNIX);
            $first->setStart($start->get(IL_CAL_DATETIME));
            $first->setStartingTime($start->get(IL_CAL_UNIX));
        }
        if ($this->props->updateDTOProperty('end') || $force) {
            $end = new ilDateTime((int) $object->getEnd(), IL_CAL_UNIX);
            $first->setEnd($end->get(IL_CAL_DATETIME));
            $first->setEndingTime($end->get(IL_CAL_UNIX));
        }
        if ($this->props->updateDTOProperty('fullDay') || $force) {
            $first->toggleFullTime($object->isFullDay());
        }
        $first->setSessionId($ilObjSession->getId());
        $appointments[0] = $first;
        $ilObjSession->setAppointments($appointments);

        return $ilObjSession;
    }

    /**
     * @param            $ilObjSession $ilObjCourse
     */
    protected function moveSession(ilObjSession $ilObjSession, SessionDTO $session)
    {
        $a_parent_ref = $this->buildParentRefId($session);
        if ($this->tree->isDeleted($ilObjSession->getRefId())) {
            $ilRepUtil = new ilRepUtil();
            $ilRepUtil->restoreObjects($a_parent_ref, [$ilObjSession->getRefId()]);
        }
        $oldParentRefId = $this->tree->getParentId($ilObjSession->getRefId());
        if ($oldParentRefId === $a_parent_ref) {
            return;
        }
        $this->tree->moveTree($ilObjSession->getRefId(), $a_parent_ref);
        $this->rbacadmin->adjustMovedObjectPermissions($ilObjSession->getRefId(), $oldParentRefId);
    }
}
