<?php

namespace srag\Plugins\Hub2\Sync\Processor\OrgUnit;

use ilObjectFactory;
use ilObjOrgUnit;
use ilOrgUnitType;
use ilOrgUnitTypeTranslation;
use ilRepUtil;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OrgUnit\IOrgUnitOrigin;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\IOrgUnitProperties;
use srag\Plugins\Hub2\Sync\IDataTransferObjectSort;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class OrgUnitSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitSyncProcessor extends ObjectSyncProcessor implements IOrgUnitSyncProcessor
{
    /**
     * @var IOrgUnitProperties
     */
    protected $props;
    /**
     * @var IOrgUnitOriginConfig
     */
    protected $config;
    /**
     * @var array
     */
    protected static $properties = [];
    /**
     * @var ilObjOrgUnit|null
     */
    protected $current_ilias_object;
    /**
     * @var \ilTree
     */
    private $tree;
    /**
     * @var \ilRbacAdmin
     */
    private $rbacadmin;

    /**
     * @param IOrgUnitOrigin $origin
     */
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

    public static function getProperties() : array
    {
        return self::$properties;
    }

    protected function getParentRefIdFallback() : int
    {
        static $ref_id_fallback;
        if (!isset($ref_id_fallback)) {
            $ref_id_fallback = $this->config->getRefIdIfNoParentId();
            if ($ref_id_fallback === 0) {
                $ref_id_fallback = ilObjOrgUnit::getRootOrgRefId();
                ;
            }
        }
        return $ref_id_fallback;
    }

    /**
     * @param IDataTransferObjectSort[] $sort_dtos
     * @throws HubException
     */
    public function handleSort(array $sort_dtos) : bool
    {
        $sort_dtos = array_filter(
            $sort_dtos,
            function (IDataTransferObjectSort $sort_dto) : bool {
                /**
                 * @var IOrgUnitDTO $dto
                 */
                $dto = $sort_dto->getDtoObject();

                return ($dto->getParentIdType() === IOrgUnitDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID && !$this->isRootId(
                    $dto
                ));
            }
        );

        $dtos = array_reduce(
            $sort_dtos,
            function (array $dtos, IDataTransferObjectSort $sort_dto) : array {
                $dtos[$sort_dto->getDtoObject()->getExtId()] = $sort_dto->getDtoObject();

                return $dtos;
            },
            []
        );

        foreach ($sort_dtos as $sort_dto) {
            /**
             * @var IOrgUnitDTO $parent_dto
             */
            $parent_dto = $dtos[$sort_dto->getDtoObject()->getParentId()];

            $level = 1;

            while (!empty($parent_dto) && !$this->isRootId(
                $parent_dto
            ) && $level <= IDataTransferObjectSort::MAX_LEVEL) {
                $sort_dto->setLevel(++$level);

                $parent_dto = $dtos[$parent_dto->getParentId()];
            }
        }

        return true;
    }

    /**
     * @throws HubException
     */
    private function isRootId(IOrgUnitDTO $dto) : bool
    {
        //$parent_id = $this->getParentId($dto);

        return (empty($dto->getParentId()));
        //	|| ($parent_id === $this->config->getRefIdIfNoParentId()
        //	|| $parent_id === ilObjOrgUnit::getRootOrgRefId()));
    }

    /**
     * @inheritdoc
     * @param IOrgUnitDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = new ilObjOrgUnit();

        $this->current_ilias_object->setTitle($dto->getTitle());

        $this->current_ilias_object->setDescription($dto->getDescription());

        $this->current_ilias_object->setOwner($dto->getOwner());

        $this->current_ilias_object->setOrgUnitTypeId($this->getOrgUnitTypeId($dto));

        $this->current_ilias_object->setImportId($this->getImportId($dto));

        $this->current_ilias_object->create();

        $this->current_ilias_object->createReference();

        $parent_id = $this->getParentId($dto);

        $this->current_ilias_object->putInTree($parent_id);
        $this->writeRBACLog($this->current_ilias_object->getRefId());
    }

    /**
     * @inheritdoc
     * @param IOrgUnitDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $this->getOrgUnitObject($ilias_id);

        if (!$this->current_ilias_object instanceof \ilObjOrgUnit) {
            return;
        }

        if ($this->props->updateDTOProperty(IOrgUnitProperties::PROP_TITLE)) {
            $this->current_ilias_object->setTitle($dto->getTitle());
        }

        if ($this->props->updateDTOProperty(IOrgUnitProperties::PROP_DESCRIPTION)) {
            $this->current_ilias_object->setDescription($dto->getDescription());
        }

        if ($this->props->updateDTOProperty(IOrgUnitProperties::PROP_OWNER)) {
            $this->current_ilias_object->setOwner($dto->getOwner());
        }

        if ($this->props->updateDTOProperty(IOrgUnitProperties::PROP_ORG_UNIT_TYPE)) {
            $this->current_ilias_object->setOrgUnitTypeId($this->getOrgUnitTypeId($dto));
        }

        $this->current_ilias_object->update();

        if ($this->props->get(IOrgUnitProperties::MOVE)) {
            $this->moveOrgUnit($dto);
        }
    }

    /**
     * @inheritdoc
     * @param IOrgUnitDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        switch ($this->props->get(IOrgUnitProperties::DELETE_MODE)) {
            case IOrgUnitProperties::DELETE_MODE_DELETE:
                $this->current_ilias_object = $this->getOrgUnitObject($ilias_id);

                if (!$this->current_ilias_object instanceof \ilObjOrgUnit) {
                    return;
                }

                $this->current_ilias_object->delete();

                break;

            case IOrgUnitProperties::DELETE_MODE_NONE:
            default:
                break;
        }
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

    protected function getOrgUnitTypeId(IOrgUnitDTO $dto) : int
    {
        $orgu_type_id = 0;

        foreach (ilOrgUnitType::getAllTypes() as $org_type) {
            /**
             * @var ilOrgUnitType $org_type
             */
            if (ilOrgUnitTypeTranslation::getInstance(
                $org_type->getId(),
                $org_type->getDefaultLang()
            )->getMember("title")
                === $dto->getOrgUnitType()
            ) {
                $orgu_type_id = (int) $org_type->getId();
                break;
            }
        }

        return $orgu_type_id;
    }

    /**
     * @throws HubException
     */
    protected function getParentId(IOrgUnitDTO $dto) : int
    {
        $parent_id = 0;
        $ref_id_fallback = $this->getParentRefIdFallback();
        switch ($dto->getParentIdType()) {
            case IOrgUnitDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID:
                $ext_id = $dto->getParentId();
                if (!empty($ext_id)) {
                    $object_factory = new ObjectFactory($this->origin);

                    $parent_org_unit = $object_factory->orgUnit($ext_id);

                    $parent_id = $parent_org_unit->getILIASId();

                    if (empty($parent_id) || !$this->getOrgUnitObject($parent_id) instanceof \ilObjOrgUnit) {
                        // throw new HubException("External ID {$ext_id} not found!");
                        return $ref_id_fallback;
                    }

                    $parent_id = (int) current(ilObjOrgUnit::_getAllReferences($parent_id));
                }
                break;

            case IOrgUnitDTO::PARENT_ID_TYPE_REF_ID:
            default:
                $parent_id = (int) $dto->getParentId();
                break;
        }

        if (empty($parent_id)) {
            $parent_id = $ref_id_fallback;
        }

        return $parent_id;
    }

    /**
     * @throws HubException
     */
    protected function moveOrgUnit(IOrgUnitDTO $dto)
    {
        $parent_ref_id = $this->getParentId($dto);
        $current_ilias_ref_id = $this->current_ilias_object->getRefId();
        if ($this->tree->isDeleted($current_ilias_ref_id)) {
            $ilRepUtil = new ilRepUtil();
            $node_data = $this->tree->getNodeTreeData($current_ilias_ref_id);
            $deleted_ref_id = (int) -$node_data['tree'];

            // if a parent node of the org unit was deleted, we first have to recover this parent
            if ($deleted_ref_id !== $current_ilias_ref_id) {
                $node_data_deleted_parent = $this->tree->getNodeTreeData($deleted_ref_id);
                $ilRepUtil->restoreObjects($node_data_deleted_parent['parent'], [$deleted_ref_id]);
                // then move the actual orgunit
                $this->tree->moveTree($current_ilias_ref_id, $parent_ref_id);
                // then delete the parent again
                $this->tree->moveToTrash($deleted_ref_id);
            } else {
                // recover and move the actual org unit
                $ilRepUtil->restoreObjects($parent_ref_id, [$current_ilias_ref_id]);
            }
        }
        $old_parent_id = (int) $this->tree->getParentId($current_ilias_ref_id);
        if ($old_parent_id === $parent_ref_id) {
            return;
        }
        $this->tree->moveTree($current_ilias_ref_id, $parent_ref_id);
        $this->rbacadmin->adjustMovedObjectPermissions($current_ilias_ref_id, $old_parent_id);
    }
}
