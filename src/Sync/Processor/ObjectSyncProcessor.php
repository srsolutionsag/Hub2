<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;
use ilObject;
use ilObjOrgUnit;
use ilObjUser;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Exception\ILIASObjectNotFoundException;
use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Notification\OriginNotifications;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class ObjectProcessor
 *
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ObjectSyncProcessor implements IObjectSyncProcessor {

	use DICTrait;
	use Hub2Trait;
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
	 * @param OriginNotifications     $originNotifications
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition, OriginNotifications $originNotifications) {
		$this->origin = $origin;
		$this->transition = $transition;
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
			} elseif ($ilias_id < 0) {
				throw new HubException("Mapping strategy " . get_class($m) . " returns negative value");
			}
			$object->store();
		}

		if ($object->getStatus() !== IObject::STATUS_TO_OUTDATED) {
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

		$time = time();

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

				$object->setStatus(IObject::STATUS_CREATED);
				$object->setProcessedDate($time);
				break;

			case IObject::STATUS_TO_UPDATE:
			case IObject::STATUS_TO_RESTORE:
				// Updating the ILIAS object is only needed if some properties were changed
				if (($object->computeHashCode() != $object->getHashCode()) || $force || $object->getStatus() === iObject::STATUS_TO_RESTORE) {
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

					$object->setStatus(IObject::STATUS_UPDATED);
					$object->setProcessedDate($time);
				} else {
					$object->setStatus(IObject::STATUS_IGNORED);
				}
				break;

			case IObject::STATUS_TO_OUTDATED:
				$this->implementation->beforeDeleteILIASObject($hook);

				$ilias_object = $this->handleDelete($object->getILIASId());

				if ($ilias_object === NULL) {
					throw new ILIASObjectNotFoundException($object);
				}

				$this->implementation->afterDeleteILIASObject($hook->withILIASObject($ilias_object));

				$object->setStatus(IObject::STATUS_OUTDATED);
				$object->setProcessedDate($time);
				break;

			case IObject::STATUS_IGNORED:
			case IObject::STATUS_FAILED:
				// Nothing to do here, object is ignored
				break;

			default:
				throw new HubException("Unrecognized intermediate status '{$object->getStatus()}' while processing {$object}");
		}

		$object->store();
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
