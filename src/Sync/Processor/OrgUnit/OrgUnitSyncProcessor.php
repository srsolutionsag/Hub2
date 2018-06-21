<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\OrgUnit;

use ilObject;
use ilObjectFactory;
use ilObjOrgUnit;
use ilOrgUnitType;
use ilOrgUnitTypeTranslation;
use ilRepUtil;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\ObjectFactory;
use SRAG\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use SRAG\Plugins\Hub2\Origin\Config\IOrgUnitOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\OrgUnit\IOrgUnitOrigin;
use SRAG\Plugins\Hub2\Origin\Properties\IOrgUnitOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class OrgUnitSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\OrgUnit
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitSyncProcessor extends ObjectSyncProcessor implements IOrgUnitSyncProcessor {

	use DIC;
	/**
	 * @var IOrgUnitOriginProperties
	 */
	private $props;
	/**
	 * @var IOrgUnitOriginConfig
	 */
	private $config;
	/**
	 * @var array
	 */
	protected static $properties = [];


	/**
	 * @param IOrgUnitOrigin          $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition, ILog $originLog, OriginNotifications $originNotifications) {
		parent::__construct($origin, $implementation, $transition, $originLog, $originNotifications);
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
	 * @param IOrgUnitDTO $dto
	 *
	 * @return ilObject
	 * @throws HubException
	 */
	protected function handleCreate(IDataTransferObject $dto): ilObject {
		$org_unit = new ilObjOrgUnit();

		$org_unit->setTitle($dto->getTitle());
		$org_unit->setDescription($dto->getDescription());
		$org_unit->setOwner($dto->getOwner());
		$org_unit->setOrgUnitTypeId($this->getOrgUnitTypeId($dto));
		$org_unit->setImportId($dto->getExtId());

		$org_unit->create();
		$org_unit->createReference();

		$parent_id = $this->getParentId($dto);
		$org_unit->putInTree($parent_id);

		return $org_unit;
	}


	/**
	 * @param IOrgUnitDTO $dto
	 * @param int         $ilias_id
	 *
	 * @return ilObject|null
	 * @throws HubException
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		$org_unit = $this->getOrgUnitObject($ilias_id);
		if ($org_unit === NULL) {
			return NULL;
		}

		if ($this->props->updateDTOProperty(IOrgUnitOriginProperties::PROP_TITLE)) {
			$org_unit->setTitle($dto->getTitle());
		}
		if ($this->props->updateDTOProperty(IOrgUnitOriginProperties::PROP_DESCRIPTION)) {
			$org_unit->setDescription($dto->getDescription());
		}
		if ($this->props->updateDTOProperty(IOrgUnitOriginProperties::PROP_OWNER)) {
			$org_unit->setOwner($dto->getOwner());
		}
		if ($this->props->updateDTOProperty(IOrgUnitOriginProperties::PROP_ORG_UNIT_TYPE)) {
			$org_unit->setOrgUnitTypeId($this->getOrgUnitTypeId($dto));
		}

		$org_unit->update();

		if ($this->props->updateDTOProperty(IOrgUnitOriginProperties::PROP_PARENT_ID)
			|| $this->props->updateDTOProperty(IOrgUnitOriginProperties::PROP_PARENT_ID_TYPE)) {
			$this->moveOrgUnit($org_unit, $dto);
		}

		return $org_unit;
	}


	/**
	 * @param int $ilias_id
	 *
	 * @return ilObject|null
	 */
	protected function handleDelete($ilias_id) {
		$org_unit = $this->getOrgUnitObject($ilias_id);
		if ($org_unit === NULL) {
			return NULL;
		}

		$this->tree()->moveToTrash($org_unit->getRefId(), true);

		return $org_unit;
	}


	/**
	 * @param int $obj_id
	 *
	 * @return ilObjOrgUnit|null
	 */
	protected function getOrgUnitObject(int $obj_id) {
		$ref_id = current(ilObjOrgUnit::_getAllReferences($obj_id));
		if (!$ref_id) {
			return NULL;
		}

		$orgUnit = ilObjectFactory::getInstanceByRefId($ref_id);

		if ($orgUnit !== false && $orgUnit instanceof ilObjOrgUnit) {
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
				if ($ext_id !== 0) {
					$object_factory = new ObjectFactory($this->origin);

					$parent_org_unit = $object_factory->orgUnit($ext_id);

					$parent_id = $parent_org_unit->getILIASId();

					if ($parent_id === NULL || $this->getOrgUnitObject($parent_id) === NULL) {
						throw new HubException("External ID {$ext_id} not found!");
					}

					$parent_id = current(ilObjOrgUnit::_getAllReferences($parent_id));
				}
				break;

			case IOrgUnitDTO::PARENT_ID_TYPE_REF_ID:
			default:
				$parent_id = $dto->getParentId();
				break;
		}

		if ($parent_id === 0 || $parent_id === NULL) {
			$parent_id = intval(ilObjOrgUnit::getRootOrgRefId());
		}

		return $parent_id;
	}


	/**
	 * @param ilObjOrgUnit $org_unit
	 * @param IOrgUnitDTO  $dto
	 *
	 * @throws HubException
	 */
	protected function moveOrgUnit(ilObjOrgUnit $org_unit, IOrgUnitDTO $dto) {
		$parent_id = $this->getParentId($dto);
		$old_parent_id = intval($this->tree()->getParentId($org_unit->getRefId()));

		unset($this->tree()->is_saved_cache[$org_unit->getRefId()]); // Fix multiple tries to restore
		if ($this->tree()->isDeleted($org_unit->getRefId())) {
			$rep_util = new ilRepUtil();
			$rep_util->restoreObjects($parent_id, [ $org_unit->getRefId() ]);
		}

		if ($parent_id !== $old_parent_id) {
			$this->tree()->moveTree($org_unit->getRefId(), $parent_id);

			$this->rbac()->admin()->adjustMovedObjectPermissions($org_unit->getRefId(), $old_parent_id);
		}
	}
}
