<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\OrgUnit;

use ilObject;
use ilObjectFactory;
use ilObjOrgUnit;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use SRAG\Plugins\Hub2\Origin\Config\IOrgUnitOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\OrgUnit\IOrgUnitOrigin;
use SRAG\Plugins\Hub2\Origin\OriginRepository;
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
		/**
		 * @var ilObjOrgUnit $orgUnit
		 */

		$orgUnit = new ilObjOrgUnit();

		$orgUnit->setTitle($dto->getTitle());
		$orgUnit->setDescription($dto->getDescription());
		$orgUnit->setOwner($dto->getOwner());
		//$dto->getParentId();
		//$orgUnit->setOrgUnitTypeId($dto->getOrguType());

		$orgUnit->create();
		$orgUnit->createReference();
		switch ($dto->getParentIdType()) {
			case IOrgUnitDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID:
				$linkedOriginId = $this->config->getLinkedOriginId();
				if (!$linkedOriginId) {
					throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked!");
				}
				$originRepository = new OriginRepository();
				/**
				 * @var IOrgUnitOrigin $origin
				 */
				$origin = array_pop(array_filter($originRepository->orgUnits(), function (IOrgUnitOrigin $origin) use ($linkedOriginId) {
					return ($origin->getId() == $linkedOriginId);
				}));
				if ($origin === NULL) {
					$msg = "The linked origin syncing origin unit was not found, please check that the correct origin is linked!";
					throw new HubException($msg);
				}

				$orgUnit->putInTree($origin->getId());
				break;

			case IOrgUnitDTO::PARENT_ID_TYPE_REF_ID:
			default:
				$orgUnit->putInTree($dto->getParentId());
				break;
		}

		return $orgUnit;
	}


	/**
	 * @param IOrgUnitDTO $dto
	 * @param int         $ilias_id
	 *
	 * @return ilObject|null
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/**
		 * @var ilObjOrgUnit $orgUnit
		 */

		$orgUnit = ilObjectFactory::getInstanceByObjId($ilias_id);
		if ($orgUnit === false) {
			return NULL;
		}

		$orgUnit->setTitle($dto->getTitle());
		$orgUnit->setDescription($dto->getDescription());
		$orgUnit->setOwner($dto->getOwner());

		$orgUnit->update();

		return $orgUnit;
	}


	/**
	 * @param int $ilias_id
	 *
	 * @return ilObject|null
	 */
	protected function handleDelete($ilias_id) {
		/**
		 * @var ilObjOrgUnit $orgUnit
		 */

		$orgUnit = ilObjectFactory::getInstanceByObjId($ilias_id);
		if ($orgUnit === false) {
			return NULL;
		}

		$orgUnit->delete();

		return $orgUnit;
	}
}
