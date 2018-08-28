<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;
use ilObject;
use ilObjOrgUnit;
use ilObjUser;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Exception\ILIASObjectNotFoundException;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\HookObject;
use SRAG\Plugins\Hub2\Object\IMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\IObject;
use SRAG\Plugins\Hub2\Object\ITaxonomyAwareObject;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;

/**
 * Class ObjectProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ObjectSyncProcessor implements IObjectSyncProcessor {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	use Helper;
	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var IObjectStatusTransition
	 */
	protected $transition;
	/**
	 * @var ILog
	 */
	protected $originLog;
	/**
	 * @var OriginNotifications
	 */
	protected $originNotifications;
	/**
	 * @var IOriginImplementation
	 */
	protected $implementation;


	/**
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition, ILog $originLog, OriginNotifications $originNotifications) {
		$this->origin = $origin;
		$this->transition = $transition;
		$this->originLog = $originLog;
		$this->originNotifications = $originNotifications;
		$this->implementation = $implementation;
	}


	/**
	 * @inheritdoc
	 */
	final public function process(IObject $object, IDataTransferObject $dto, bool $force = false) {
		// The HookObject is filled with the object (known Data in HUB) and the DTO delivered with
		// your origin. Additionally, if available, the HookObject is filled with the given
		// ILIAS-Object, too.
		$hook = new HookObject($object, $dto);

		// We pass the HookObject to the OriginImplementaion which could override the status
		$this->implementation->overrideStatus($hook);

		// We keep the old data if the object is getting deleted, as there is no "real" DTO available, because
		// the data has not been delivered...

		// We check if there is another mapping strategy than "None" and check for existing objects in ILIAS
		if ($object->getStatus() === IObject::STATUS_TO_CREATE && $dto instanceof IMappingStrategyAwareDataTransferObject) {
			$m = $dto->getMappingStrategy();
			$ilias_id = $m->map($dto);
			if ($ilias_id > 0) {
				$object->setStatus(IObject::STATUS_TO_UPDATE);
				$object->setILIASId($ilias_id);
				$object->save();
			}
		}

		if ($object->getStatus() != IObject::STATUS_TO_DELETE) {
			$object->setData($dto->getData());
			if ($dto instanceof IMetadataAwareDataTransferObject
				&& $object instanceof IMetadataAwareObject) {
				$object->setMetaData($dto->getMetaData());
			}
			if ($dto instanceof ITaxonomyAwareDataTransferObject
				&& $object instanceof ITaxonomyAwareObject) {
				$object->setTaxonomies($dto->getTaxonomies());
			}
		}
		switch ($object->getStatus()) {
			case IObject::STATUS_TO_CREATE:
				$this->implementation->beforeCreateILIASObject($hook);
				$ilias_object = $this->handleCreate($dto);
				if ($this instanceof IMetadataSyncProcessor) {
					$this->handleMetadata($dto, $ilias_object);
				}
				if ($this instanceof ITaxonomySyncProcessor) {
					$this->handleTaxonomies($dto, $ilias_object);
				}
				$object->setILIASId($this->getILIASId($ilias_object));
				$this->implementation->afterCreateILIASObject($hook->withILIASObject($ilias_object));
				break;
			case IObject::STATUS_TO_UPDATE:
				// Updating the ILIAS object is only needed if some properties were changed
				if (($object->computeHashCode() != $object->getHashCode()) || $force) {
					$this->implementation->beforeUpdateILIASObject($hook);
					$ilias_object = $this->handleUpdate($dto, $object->getILIASId());
					if ($ilias_object === NULL) {
						throw new ILIASObjectNotFoundException($object);
					}
					if ($this instanceof IMetadataSyncProcessor) {
						$this->handleMetadata($dto, $ilias_object);
					}
					if ($this instanceof ITaxonomySyncProcessor) {
						$this->handleTaxonomies($dto, $ilias_object);
					}
					$object->setILIASId($this->getILIASId($ilias_object));
					$this->implementation->afterUpdateILIASObject($hook->withILIASObject($ilias_object));
				} else {
					$object->updateStatus(IObject::STATUS_NOTHING_TO_UPDATE);
				}
				break;
			case IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED:
				// Updating the ILIAS object if newly delivered. Currently newly delivered will lead
				// to an Update-Handler which could lead to problems for some configuration such as
				// deleted (in ILIAS) objects. Some refactoring will be needed to handle this issue,
				// e.g. to ask the relevant SyncProcessor wheather the related ILIAS object is
				// avaiable or not. Another approach is the possibility to give MappingStrategies
				// with your DTO (ans some default as well) which then before the Handler is called
				// will try to map you DTO with an existing ILIAS Object (which will also be needed
				// for handling existing objects while Creation as well.

				$this->implementation->beforeUpdateILIASObject($hook);
				$ilias_object = $this->handleUpdate($dto, $object->getILIASId());
				if ($ilias_object === NULL) {
					throw new ILIASObjectNotFoundException($object);
				}
				if ($this instanceof IMetadataSyncProcessor) {
					$this->handleMetadata($dto, $ilias_object);
				}
				if ($this instanceof ITaxonomySyncProcessor) {
					$this->handleTaxonomies($dto, $ilias_object);
				}
				$object->setILIASId($this->getILIASId($ilias_object));
				$this->implementation->afterUpdateILIASObject($hook->withILIASObject($ilias_object));
				break;
			case IObject::STATUS_TO_DELETE:
				$this->implementation->beforeDeleteILIASObject($hook);
				$ilias_object = $this->handleDelete($object->getILIASId());
				if ($ilias_object === NULL) {
					throw new ILIASObjectNotFoundException($object);
				}
				$this->implementation->afterDeleteILIASObject($hook->withILIASObject($ilias_object));

				break;
			case IObject::STATUS_IGNORED:
				// Nothing to do here, object is ignored
				break;
			default:
				throw new HubException("Unrecognized intermediate status '{$object->getStatus()}' while processing {$object}");
		}
		$object->setStatus($this->transition->intermediateToFinal($object));
		if ($object->getStatus() != IObject::STATUS_IGNORED
			&& $object->getStatus() != IObject::STATUS_NOTHING_TO_UPDATE) {
			$object->setProcessedDate(time());
		}
		if ($object->getStatus() != IObject::STATUS_NOTHING_TO_UPDATE) {
			$object->save();
		}
	}


	/**
	 * @param ilObject|FakeIliasObject $object
	 *
	 * @return int
	 */
	protected function getILIASId($object) {
		if ($object instanceof ilObjUser || $object instanceof ilObjOrgUnit || $object instanceof FakeIliasObject
			|| $object instanceof FakeIliasMembershipObject) {
			return $object->getId();
		}

		return $object->getRefId();
	}


	/**
	 * The import ID is set on the ILIAS object.
	 *
	 * @param IDataTransferObject $object
	 *
	 * @return string
	 */
	protected function getImportId(IDataTransferObject $object) {
		return self::IMPORT_PREFIX . $this->origin->getId() . '_' . $object->getExtId();
	}


	/**
	 * @inheritdoc
	 */
	public function handleSort(array $sort_dtos): bool {
		return false;
	}


	/**
	 * Create a new ILIAS object from the given data transfer object.
	 *
	 * @param IDataTransferObject $dto
	 *
	 * @return ilObject
	 */
	abstract protected function handleCreate(IDataTransferObject $dto);


	/**
	 * Update the corresponding ILIAS object.
	 * Return the processed ILIAS object or null if the object was not found, e.g. it is deleted in
	 * ILIAS.
	 *
	 * @param IDataTransferObject $dto
	 * @param int                 $iliasId
	 *
	 * @return ilObject
	 */
	abstract protected function handleUpdate(IDataTransferObject $dto, $iliasId);


	/**
	 * Delete the corresponding ILIAS object.
	 * Return the deleted ILIAS object or null if the object was not found in ILIAS.
	 *
	 * @param int $iliasId
	 *
	 * @return ilObject
	 */
	abstract protected function handleDelete($iliasId);
}
