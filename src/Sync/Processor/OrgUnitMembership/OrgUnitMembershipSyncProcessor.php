<?php

namespace srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership;

use ilObjectFactory;
use ilObjOrgUnit;
use ilOrgUnitPosition;
use ilOrgUnitUserAssignment;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;
use srag\Plugins\Hub2\Origin\Config\OrgUnitMembership\IOrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership\IOrgUnitMembershipProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasObject;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class OrgUnitMembershipSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitMembershipSyncProcessor extends ObjectSyncProcessor implements IOrgUnitMembershipSyncProcessor
{
    /**
     * @var IOrgUnitMembershipProperties
     */
    private $props;
    /**
     * @var IOrgUnitMembershipOriginConfig
     */
    private $config;
    /**
     * @var array
     */
    protected static $properties = [];
    /**
     * @var FakeOrgUnitMembershipObject|null
     */
    protected $current_ilias_object;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
    }

    public static function getProperties() : array
    {
        return self::$properties;
    }

    /**
     * @inheritdoc
     * @param IOrgUnitMembershipDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $this->getFakeIliasObject($this->assignToOrgUnit($dto));
    }

    /**
     * @inheritdoc
     * @param IOrgUnitMembershipDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        if ($this->props->updateDTOProperty(IOrgUnitMembershipProperties::PROP_ORG_UNIT_ID)
            || $this->props->updateDTOProperty(IOrgUnitMembershipProperties::PROP_ORG_UNIT_ID_TYPE)
            || $this->props->updateDTOProperty(IOrgUnitMembershipProperties::PROP_USER_ID)
            || $this->props->updateDTOProperty(IOrgUnitMembershipProperties::PROP_POSITION)
        ) {
            $this->handleDelete($dto, $ilias_id);

            $this->handleCreate($dto);
        } else {
            throw new HubException("{$ilias_id} should not be updated!");
        }
    }

    /**
     * @inheritdoc
     * @param IOrgUnitMembershipDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        switch ($this->props->get(IOrgUnitMembershipProperties::DELETE_MODE)) {
            case IOrgUnitMembershipProperties::DELETE_MODE_DELETE:
                $this->current_ilias_object = FakeOrgUnitMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

                $assignment = ilOrgUnitUserAssignment::where(
                    [
                        "orgu_id" => $this->current_ilias_object->getContainerIdIlias(),
                        "user_id" => $this->current_ilias_object->getUserIdIlias(),
                        "position_id" => $this->current_ilias_object->getPositionId(),
                    ]
                )->first();

                $assignment->delete();
                break;

            case IOrgUnitMembershipProperties::DELETE_MODE_NONE:
            default:
                break;
        }
    }

    /**
     * @throws HubException
     */
    protected function assignToOrgUnit(IOrgUnitMembershipDTO $dto) : ilOrgUnitUserAssignment
    {
        switch ($dto->getPosition()) {
            case IOrgUnitMembershipDTO::POSITION_EMPLOYEE:
                $position_id = ilOrgUnitPosition::getCorePositionId(self::IL_POSITION_EMPLOYEE);
                break;

            case IOrgUnitMembershipDTO::POSITION_SUPERIOR:
                $position_id = ilOrgUnitPosition::getCorePositionId(self::IL_POSITION_SUPERIOR);
                break;

            default:
                throw new HubException("Invalid position {$dto->getPosition()}!");
        }

        return ilOrgUnitUserAssignment::findOrCreateAssignment(
            $dto->getUserId(),
            $position_id,
            $this->getOrgUnitId($dto)
        );
    }

    /**
     * @throws HubException
     */
    protected function getFakeIliasObject(ilOrgUnitUserAssignment $assignment) : FakeIliasObject
    {
        return new FakeOrgUnitMembershipObject(
            $assignment->getOrguId(),
            $assignment->getUserId(),
            $assignment->getPositionId()
        );
    }

    /**
     * @throws HubException
     */
    protected function getOrgUnitId(IOrgUnitMembershipDTO $dto) : int
    {
        switch ($dto->getOrgUnitIdType()) {
            case IOrgUnitMembershipDTO::ORG_UNIT_ID_TYPE_EXTERNAL_EXT_ID:
                $ext_id = $dto->getOrgUnitId();

                $linkedOriginId = $this->config->getLinkedOriginId();
                if ($linkedOriginId === 0) {
                    throw new HubException("Unable to lookup external ref-ID because there is no origin linked");
                }

                $origin_factory = new OriginFactory();
                $origin = $origin_factory->getById($linkedOriginId);

                $object_factory = new ObjectFactory($origin);

                $org_unit = $object_factory->orgUnit($ext_id);

                $org_unit_id = $org_unit->getILIASId();

                if (empty($org_unit_id)) {
                    throw new HubException("External ID {$ext_id} not found!");
                }
                break;

            case IOrgUnitMembershipDTO::ORG_UNIT_ID_TYPE_OBJ_ID:
            default:
                $org_unit_id = (int) $dto->getOrgUnitId();
                break;
        }

        $org_unit = $this->getOrgUnitObject($org_unit_id);
        if (!$org_unit instanceof \ilObjOrgUnit) {
            throw new HubException("Org Unit {$org_unit_id} not found!");
        }

        return $org_unit->getRefId();
    }

    /**
     * @return ilObjOrgUnit|null
     */
    protected function getOrgUnitObject(int $obj_id)
    {
        $ref_id = current(ilObjOrgUnit::_getAllReferences($obj_id));
        if (empty($ref_id)) {
            return null;
        }

        $orgUnit = ilObjectFactory::getInstanceByRefId($ref_id);

        if (!empty($orgUnit) && $orgUnit instanceof ilObjOrgUnit) {
            return $orgUnit;
        } else {
            return null;
        }
    }
}
