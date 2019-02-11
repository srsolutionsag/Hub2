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
 *
 * @package srag\Plugins\Hub2\Sync\Processor\OrgUnit
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitSyncProcessor extends ObjectSyncProcessor implements IOrgUnitSyncProcessor {

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
	protected $current_ilias_object = NULL;


	/**
	 * @param IOrgUnitOrigin          $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition) {
		parent::__construct($origin, $implementation, $transition);
		$this->props = $origin->properties();
		$this->config = $origin->config();
	}


	/**
	 * @return array
	 */
	public static function getProperties(): array {
		return self::$properties;
	}


	/**
	 * @param IDataTransferObjectSort[] $sort_dtos
	 *
	 * @return bool
	 * @throws HubException
	 */
	public function handleSort(array $sort_dtos): bool {
		/**
		 * @var IOrgUnitDTO $dto
		 * @var IOrgUnitDTO $parent_dto
		 */

		$dtos = [];
		foreach ($sort_dtos as $sort_dto) {
			$dto = $sort_dto->getDtoObject();

			if ($dto->getParentIdType() === IOrgUnitDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
				$dtos[$dto->getExtId()] = $dto;
			}
		}

		// Calculate Level of each manager
		foreach ($sort_dtos as $sort_dto) {
			$dto = $sort_dto->getDtoObject();

			$level = 1;

			// OrgUnit Node -> Level 1
			if ($this->isRootId($dto) || $dto->getParentIdType() !== IOrgUnitDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
				$sort_dto->setLevel($level);

				continue;
			}

			// If not Level 1 - calculate it for this manager!
			$parent_dto = $dtos[$dto->getParentId()];

			// Level 100 max level, prophit unlimited while!
			while ($level <= IDataTransferObjectSort::MAX_LEVEL) {
				$level += 1;

				// Do it until OrgUnit Node
				if ($this->isRootId($parent_dto) || $level === IDataTransferObjectSort::MAX_LEVEL) {
					$sort_dto->setLevel($level);
					break;
				} else {
					$parent_dto = $dtos[$parent_dto->getParentId()];
				}
			}
		}

		return true;
	}


	/**
	 * @param IOrgUnitDTO $dto
	 *
	 * @return bool
	 * @throws HubException
	 */
	private function isRootId(IOrgUnitDTO $dto): bool {
		//$parent_id = $this->getParentId($dto);

		return (empty($dto->getParentId()));
		//	|| ($parent_id === $this->config->getRefIdIfNoParentId()
		//	|| $parent_id === ilObjOrgUnit::getRootOrgRefId()));
	}


	/**
	 * @inheritdoc
	 *
	 * @param IOrgUnitDTO $dto
	 */
	protected function handleCreate(IDataTransferObject $dto)/*: void*/ {
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
	}


	/**
	 * @inheritdoc
	 *
	 * @param IOrgUnitDTO $dto
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/ {
		$this->current_ilias_object = $this->getOrgUnitObject($ilias_id);

		if (empty($this->current_ilias_object)) {
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

		if ($this->props->updateDTOProperty(IOrgUnitProperties::PROP_PARENT_ID)
			|| $this->props->updateDTOProperty(IOrgUnitProperties::PROP_PARENT_ID_TYPE)) {
			$this->moveOrgUnit($dto);
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id)/*: void*/ {
		$this->current_ilias_object = $this->getOrgUnitObject($ilias_id);

		if (empty($this->current_ilias_object)) {
			return;
		}

		self::dic()->tree()->moveToTrash($this->current_ilias_object->getRefId(), true);
	}


	/**
	 * @param int $obj_id
	 *
	 * @return ilObjOrgUnit|null
	 */
	protected function getOrgUnitObject(int $obj_id) {
		$ref_id = current(ilObjOrgUnit::_getAllReferences($obj_id));
		if (empty($ref_id)) {
			return NULL;
		}

		$orgUnit = ilObjectFactory::getInstanceByRefId($ref_id);

		if (!empty($orgUnit) && $orgUnit instanceof ilObjOrgUnit) {
			return $orgUnit;
		} else {
			return NULL;
		}
	}


	/**
	 * @param IOrgUnitDTO $dto
	 *
	 * @return int
	 */
	protected function getOrgUnitTypeId(IOrgUnitDTO $dto): int {
		$orgu_type_id = 0;

		foreach (ilOrgUnitType::getAllTypes() as $org_type) {
			/**
			 * @var ilOrgUnitType $org_type
			 */
			if (ilOrgUnitTypeTranslation::getInstance($org_type->getId(), $org_type->getDefaultLang())->getMember("title")
				=== $dto->getOrgUnitType()) {
				$orgu_type_id = (int)$org_type->getId();
				break;
			}
		}

		return $orgu_type_id;
	}


	/**
	 * @param IOrgUnitDTO $dto
	 *
	 * @return int
	 * @throws HubException
	 */
	protected function getParentId(IOrgUnitDTO $dto): int {
		$parent_id = 0;

		switch ($dto->getParentIdType()) {
			case IOrgUnitDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID:
				$ext_id = $dto->getParentId();
				if (!empty($ext_id)) {
					$object_factory = new ObjectFactory($this->origin);

					$parent_org_unit = $object_factory->orgUnit($ext_id);

					$parent_id = $parent_org_unit->getILIASId();

					if (empty($parent_id) || empty($this->getOrgUnitObject($parent_id))) {
						throw new HubException("External ID {$ext_id} not found!");
					}

					$parent_id = intval(current(ilObjOrgUnit::_getAllReferences($parent_id)));
				}
				break;

			case IOrgUnitDTO::PARENT_ID_TYPE_REF_ID:
			default:
				$parent_id = intval($dto->getParentId());
				break;
		}

		if (empty($parent_id)) {
			$parent_id = $this->config->getRefIdIfNoParentId();
		}
		if (empty($parent_id)) {
			$parent_id = intval(ilObjOrgUnit::getRootOrgRefId());
		}

		return $parent_id;
	}


	/**
	 * @param IOrgUnitDTO $dto
	 *
	 * @throws HubException
	 */
	protected function moveOrgUnit(IOrgUnitDTO $dto) {
		$parent_ref_id = $this->getParentId($dto);
		if (self::dic()->tree()->isDeleted($this->current_ilias_object->getRefId())) {
			$ilRepUtil = new ilRepUtil();
			$node_data = self::dic()->tree()->getNodeTreeData($this->current_ilias_object->getRefId());
			$deleted_ref_id = (int)- $node_data['tree'];

			// if a parent node of the org unit was deleted, we first have to recover this parent
			if ($deleted_ref_id != $this->current_ilias_object->getRefId()) {
				$node_data_deleted_parent = self::dic()->tree()->getNodeTreeData($deleted_ref_id);
				$ilRepUtil->restoreObjects($node_data_deleted_parent['parent'], [ $deleted_ref_id ]);
				// then move the actual orgunit
				self::dic()->tree()->moveTree($this->current_ilias_object->getRefId(), $parent_ref_id);
				// then delete the parent again
				self::dic()->tree()->moveToTrash($deleted_ref_id);
			} else {
				// recover and move the actual org unit
				$ilRepUtil->restoreObjects($parent_ref_id, [ $this->current_ilias_object->getRefId() ]);
			}
		}
		$old_parent_id = intval(self::dic()->tree()->getParentId($this->current_ilias_object->getRefId()));
		if ($old_parent_id == $parent_ref_id) {
			return;
		}
		self::dic()->tree()->moveTree($this->current_ilias_object->getRefId(), $parent_ref_id);
		self::dic()->rbacadmin()->adjustMovedObjectPermissions($this->current_ilias_object->getRefId(), $old_parent_id);
	}
}
