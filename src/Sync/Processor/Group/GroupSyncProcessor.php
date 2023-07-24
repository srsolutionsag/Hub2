<?php

namespace srag\Plugins\Hub2\Sync\Processor\Group;

use ilCalendarCategory;
use ilDate;
use ilObjGroup;
use ilRepUtil;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\Group\GroupOriginConfig;
use srag\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties;
use srag\Plugins\Hub2\Sync\Processor\DidacticTemplateSyncProcessor;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ParentResolver\GroupParentResolver;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;

/**
 * Class GroupSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupSyncProcessor extends ObjectSyncProcessor implements IGroupSyncProcessor
{
    use TaxonomySyncProcessor;
    use MetadataSyncProcessor;
    use DidacticTemplateSyncProcessor;

    /**
     * @var GroupParentResolver
     */
    private $parent_resolver;

    /**
     * @var GroupProperties
     */
    protected $props;
    /**
     * @var GroupOriginConfig
     */
    protected $config;
    /**
     * @var IGroupActivities
     */
    protected $groupActivities;
    /**
     * @var array
     */
    protected static $properties
        = [
            "title",
            "description",
            "information",
            "groupType",
            "owner",
            "viewMode",
            "registerMode",
            "registrationStart",
            "registrationEnd",
            "password",
            "minMembers",
            "maxMembers",
            "waitingListAutoFill",
            "cancellationEnd",
            "start",
            "end",
            "latitude",
            "longitude",
            "locationzoom",
            "registrationAccessCode",
            "enableGroupMap",
        ];
    /**
     * @var array
     */
    protected static $ildate_fields
        = ["cancellationEnd", "registrationStart", "registrationEnd"];
    /**
     * @var \ilTree
     */
    private $tree;
    /**
     * @var \ilObjectDataCache
     */
    private $obj_data_cache;
    /**
     * @var \ilRbacAdmin
     */
    private $rbacadmin;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition,
        IGroupActivities $groupActivities
    ) {
        global $DIC;
        $this->tree = $DIC['tree'];
        $this->obj_data_cache = $DIC['ilObjDataCache'];
        $this->rbacadmin = $DIC->rbac()->admin();
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
        $this->groupActivities = $groupActivities;
        $this->parent_resolver = new GroupParentResolver(
            $this->config->getParentRefIdIfNoParentIdFound(),
            $this->config->getLinkedOriginId()
        );
    }

    public static function getProperties() : array
    {
        return self::$properties;
    }

    public static function getNonAutoProperties() : array
    {
        return [
            "start",
            "end",
            "groupType",
            "registerMode"
        ];
    }

    /**
     * @inheritdoc
     * @param GroupDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjGroup = new ilObjGroup();
        $ilObjGroup->setImportId($this->getImportId($dto));
        $ilObjGroup->enableUnlimitedRegistration(true);
        // Find the refId under which this group should be created
        $parentRefId = $this->determineParentRefId($dto);
        // Pass properties from DTO to ilObjUser

        foreach (self::getProperties() as $property) {
            // handled separately
            if (in_array($property, self::getNonAutoProperties(), true)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $var = $dto->$getter();
                if (in_array($property, self::$ildate_fields)) {
                    $var = new ilDate($var, IL_CAL_UNIX);
                }

                $ilObjGroup->$setter($var);
            }
        }

        $ilObjGroup->enableUnlimitedRegistration($dto->getRegUnlimited());

        $ilObjGroup->enableMembershipLimitation($dto->getRegMembershipLimitation());

        $ilObjGroup->enableWaitingList($dto->getWaitingList());
        $ilObjGroup->setRegistrationType($dto->getRegisterMode());

        $ilObjGroup->enableRegistrationAccessCode($dto->getRegAccessCodeEnabled());

        $ilObjGroup->create();
        $ilObjGroup->createReference();
        $ilObjGroup->putInTree($parentRefId);
        $ilObjGroup->setPermissions($parentRefId);
        $this->writeRBACLog($ilObjGroup->getRefId());

        $this->handleAppointementsColor($ilObjGroup, $dto);

        // handle start and end separately
        if ($dto->getStart() !== null && $dto->getEnd() !== null) {
            $ilObjGroup->setPeriod($dto->getStart(), $dto->getEnd());
        }

        if ($dto->getGroupType() !== null) {
            $ilObjGroup->updateGroupType($dto->getGroupType());
        }

        $ilObjGroup->update();
    }

    /**
     * @inheritdoc
     * @param GroupDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjGroup = $this->findILIASGroup($ilias_id);
        if (!$ilObjGroup instanceof \ilObjGroup) {
            return;
        }
        // Update some properties if they should be updated depending on the origin config
        foreach (self::getProperties() as $property) {
            // handled separately
            if (in_array($property, self::getNonAutoProperties(), true)) {
                continue;
            }
            if (!$this->props->updateDTOProperty($property)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $var = $dto->$getter();
                if (in_array($property, self::$ildate_fields)) {
                    $var = new ilDate($var, IL_CAL_UNIX);
                }

                $ilObjGroup->$setter($var);
            }
        }
        if (
            $this->props->updateDTOProperty('start')
            && $this->props->updateDTOProperty('end')
            && $dto->getStart() !== null
            && $dto->getEnd() !== null
        ) {
            $ilObjGroup->setPeriod($dto->getStart(), $dto->getEnd());
        }

        if ($this->props->updateDTOProperty("registrationType")
            && $dto->getRegisterMode() !== null
        ) {
            $ilObjGroup->setRegistrationType($dto->getRegisterMode());
        }

        if ($this->props->updateDTOProperty("regUnlimited")
            && $dto->getRegUnlimited() !== null
        ) {
            $ilObjGroup->enableUnlimitedRegistration($dto->getRegUnlimited());
        }

        if ($this->props->updateDTOProperty("regMembershipLimitation")
            && $dto->getRegMembershipLimitation() !== null
        ) {
            $ilObjGroup->enableMembershipLimitation($dto->getRegMembershipLimitation());
        }

        if ($this->props->updateDTOProperty("waitingList") && $dto->getWaitingList() !== null) {
            $ilObjGroup->enableWaitingList($dto->getWaitingList());
        }

        if ($this->props->updateDTOProperty("regAccessCodeEnabled")
            && $dto->getRegAccessCodeEnabled() !== null
        ) {
            $ilObjGroup->enableRegistrationAccessCode($dto->getRegAccessCodeEnabled());
        }

        if ($this->props->updateDTOProperty("regUnlimited")
            && $dto->getRegUnlimited() !== null
        ) {
            $ilObjGroup->enableUnlimitedRegistration($dto->getRegisterMode());
        }

        if ($this->props->updateDTOProperty("appointementsColor")) {
            $this->handleAppointementsColor($ilObjGroup, $dto);
        }
        if ($this->props->updateDTOProperty("groupType")) {
            $ilObjGroup->updateGroupType($dto->getGroupType());
        }

        // move/put in tree
        // Find the refId under which this course should be created
        $parent_ref_id = $this->determineParentRefId($dto);
        // Check if we should create some dependence categories
        $ref_id = (int) $ilObjGroup->getRefId();

        if ($this->parent_resolver->isRefIdDeleted($ref_id)) {
            $this->parent_resolver->restoreRefId($ref_id, $parent_ref_id);
        } elseif ($this->props->get(GroupProperties::MOVE_GROUP)) {
            $this->parent_resolver->move($ref_id, $parent_ref_id);
        }

        $ilObjGroup->update();
    }

    protected function handleAppointementsColor(ilObjGroup $ilObjGroup, GroupDTO $dto)
    {
        if ($dto->getAppointementsColor() !== '' && $dto->getAppointementsColor() !== '0') {
            $this->obj_data_cache->deleteCachedEntry($ilObjGroup->getId());
            /**
             * @var $cal_cat ilCalendarCategory
             */
            $cal_cat = ilCalendarCategory::_getInstanceByObjId($ilObjGroup->getId());
            $cal_cat->setColor($dto->getAppointementsColor());
            $cal_cat->update();
        }
    }

    /**
     * @inheritdoc
     * @param GroupDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjGroup = $this->findILIASGroup($ilias_id);
        if (!$ilObjGroup instanceof \ilObjGroup) {
            return;
        }
        if ($this->props->get(GroupProperties::DELETE_MODE) == GroupProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(GroupProperties::DELETE_MODE)) {
            case GroupProperties::DELETE_MODE_CLOSED:
                $ilObjGroup->setGroupStatus(2);
                $ilObjGroup->update();
                break;
            case GroupProperties::DELETE_MODE_DELETE:
                $ilObjGroup->delete();
                break;
            case GroupProperties::DELETE_MODE_MOVE_TO_TRASH:
                $this->tree->moveToTrash($ilObjGroup->getRefId(), true);
                break;
            case GroupProperties::DELETE_MODE_DELETE_OR_CLOSE:
                if ($this->groupActivities->hasActivities($ilObjGroup)) {
                    $ilObjGroup->setGroupStatus(2);
                    $ilObjGroup->update();
                } else {
                    $this->tree->moveToTrash($ilObjGroup->getRefId(), true);
                }
                break;
        }
    }

    /**
     * @return int
     * @throws HubException
     */
    protected function determineParentRefId(GroupDTO $group)
    {
        return $this->parent_resolver->resolveParentRefId($group);
    }

    /**
     * @param int $iliasId
     * @return ilObjGroup|null
     */
    protected function findILIASGroup($iliasId)
    {
        if (!ilObjGroup::_exists($iliasId, true)) {
            return null;
        }

        return new ilObjGroup($iliasId);
    }

    /**
     * Move the group to a new parent.
     * Note: May also create the dependence categories
     */
    protected function moveGroup(ilObjGroup $ilObjGroup, GroupDTO $group)
    {
        $parentRefId = $this->determineParentRefId($group);
        if ($this->tree->isDeleted($ilObjGroup->getRefId())) {
            $ilRepUtil = new ilRepUtil();
            $ilRepUtil->restoreObjects($parentRefId, [$ilObjGroup->getRefId()]);
        }
        $oldParentRefId = $this->tree->getParentId($ilObjGroup->getRefId());
        if ($oldParentRefId === $parentRefId) {
            return;
        }
        $this->tree->moveTree($ilObjGroup->getRefId(), $parentRefId);
        $this->rbacadmin->adjustMovedObjectPermissions($ilObjGroup->getRefId(), $oldParentRefId);
    }
}
