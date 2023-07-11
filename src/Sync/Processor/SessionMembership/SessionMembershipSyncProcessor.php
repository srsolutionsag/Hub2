<?php

namespace srag\Plugins\Hub2\Sync\Processor\SessionMembership;

use ilObject2;
use ilObjSession;
use ilObjUser;
use ilSessionParticipants;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO;
use srag\Plugins\Hub2\Origin\Config\SessionMembership\SessionMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\Properties\SessionMembership\SessionMembershipProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class SessionMembershipSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipSyncProcessor extends ObjectSyncProcessor implements ISessionMembershipSyncProcessor
{
    /**
     * @var SessionMembershipProperties
     */
    private $props;
    /**
     * @var SessionMembershipOriginConfig
     */
    private $config;
    /**
     * @var array
     */
    protected static $properties = [];
    /**
     * @var \ilTree
     */
    private $tree;
    /**
     * @var \ilDBInterface
     */
    private $db;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        global $DIC;
        $this->tree = $DIC['tree'];
        $this->db = $DIC->database();
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
     * @param SessionMembershipDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $session_ref_id = $this->buildParentRefId($dto);
        $ilObjSession = $this->findILIASObject($session_ref_id);
        $this->handleMembership($ilObjSession, $dto);
        $this->handleContact($ilObjSession, $dto);

        $this->current_ilias_object = new FakeIliasMembershipObject($session_ref_id, $dto->getUserId());
    }

    /**
     * @inheritdoc
     * @param SessionMembershipDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

        $ilObjSession = $this->findILIASObject($obj->getContainerIdIlias());
        $this->handleMembership($ilObjSession, $dto);

        $obj->setUserIdIlias($dto->getUserId());
        $obj->setContainerIdIlias($ilObjSession->getRefId());
        $obj->initId();

        if ($this->props->updateDTOProperty("isContact")) {
            $this->handleContact($ilObjSession, $dto);
        }
    }

    /**
     * @inheritdoc
     * @param SessionMembershipDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);
        $ilObjSession = $this->findILIASObject($obj->getContainerIdIlias());
        $this->removeMembership($ilObjSession, $obj->getUserIdIlias());
    }

    /**
     * @param int $ilias_id
     * @throws HubException
     */
    protected function findILIASObject($ilias_id) : \ilObjSession
    {
        if (!ilObject2::_exists($ilias_id, true)) {
            throw new HubException("Session not found with ref_id {$ilias_id}");
        }

        return new ilObjSession($ilias_id, true);
    }

    /**
     * @throws HubException
     */
    protected function buildParentRefId(SessionMembershipDTO $dto) : int
    {
        if ($dto->getSessionIdType() == SessionMembershipDTO::PARENT_ID_TYPE_REF_ID) {
            if ($this->tree->isInTree($dto->getSessionId())) {
                return (int) $dto->getSessionId();
            }
            throw new HubException(
                "Could not find the ref-ID of the parent session in the tree: '{$dto->getGroupId()}'"
            );
        }
        if ($dto->getSessionIdType() == SessionMembershipDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored parent-ID is an external-ID from a category.
            // We must search the parent ref-ID from a category object synced by a linked origin.
            // --> Get an instance of the linked origin and lookup the category by the given external ID.
            $linkedOriginId = $this->config->getLinkedOriginId();
            if ($linkedOriginId === 0) {
                throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
            }
            $originRepository = new OriginRepository();
            $arrayFilter = array_filter(
                $originRepository->sessions(),
                function ($origin) use ($linkedOriginId) : bool {
                    /** @var IOrigin $origin */
                    return (int) $origin->getId() == $linkedOriginId;
                }
            );
            $origin = array_pop(
                $arrayFilter
            );
            if (!$origin instanceof \srag\Plugins\Hub2\Origin\Session\ISessionOrigin) {
                $msg = "The linked origin syncing sessions was not found, please check that the correct origin is linked";
                throw new HubException($msg);
            }
            $objectFactory = new ObjectFactory($origin);
            $session = $objectFactory->session($dto->getSessionId());
            if (!$session->getILIASId()) {
                throw new HubException("The linked session does not (yet) exist in ILIAS");
            }
            if (!$this->tree->isInTree($session->getILIASId())) {
                throw new HubException(
                    "Could not find the ref-ID of the parent session in the tree: '{$session->getILIASId()}'"
                );
            }

            return (int) $session->getILIASId();
        }

        return 0;
    }

    /**
     * @throws HubException
     */
    protected function handleMembership(ilObjSession $ilObjSession, SessionMembershipDTO $dto)
    {
        /**
         * @var ilSessionParticipants $ilSessionParticipants
         */
        $ilSessionParticipants = $ilObjSession->getMembersObject();

        $user_id = $dto->getUserId();
        if (!ilObjUser::_exists($user_id)) {
            throw new HubException("user with id {$user_id} does not exist");
        }

        $ilSessionParticipants->register($user_id);
    }

    /**
     * @throws HubException
     */
    protected function handleContact(ilObjSession $ilObjSession, SessionMembershipDTO $dto)
    {
        /**
         * @var ilSessionParticipants $ilSessionParticipants
         */
        $ilSessionParticipants = $ilObjSession->getMembersObject();

        $user_id = $dto->getUserId();
        if (!ilObjUser::_exists($user_id)) {
            throw new HubException("user with id {$user_id} does not exist");
        }

        /**
         * Note to who ever might be concerned, No I was not drunken while writting the next
         * few lines. After some investigation, it seemed the simplest way to set a single
         * user as participant of a session. See the gem ilSessionParticipants
         * This Option would also be possible, but is nast as well an very slow:
         * $ilSessionParticipants->getEventParticipants()->__read();
         * $user = $ilSessionParticipants->getEventParticipants()->getUser((int)$user_id);
         * $ilSessionParticipants->getEventParticipants()->setContact($dto->isContact());
         * ... //manuall set all other properties from the user array
         * $ilSessionParticipants->getEventParticipants()->updateUser();
         */
        $event_id = $ilSessionParticipants->getEventParticipants()->getEventId();
        $query = "UPDATE event_participants " . "SET contact = " . $this->db->quote(
            $dto->isContact(),
            'integer'
        ) . " "
            . "WHERE event_id = " . $this->db->quote(
                $event_id,
                'integer'
            ) . " " . "AND usr_id = " . $this->db
                ->quote($user_id, 'integer') . " ";
        $this->db->manipulate($query);
    }

    /**
     * @param int $user_id
     * @throws HubException
     */
    protected function removeMembership(ilObjSession $ilObjSession, $user_id)
    {
        /**
         * @var ilSessionParticipants $ilSessionParticipants
         */
        $ilSessionParticipants = $ilObjSession->getMembersObject();

        if (!ilObjUser::_exists($user_id)) {
            throw new HubException("user with id {$user_id} does not exist");
        }

        $ilSessionParticipants->unregister((int) $user_id);
    }
}
