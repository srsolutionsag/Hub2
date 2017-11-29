<?php namespace SRAG\Plugins\Hub2\Sync\Processor;

use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Exception\ILIASObjectNotFoundException;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Metadata\Implementation\MetadataImplementationFactory;
use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\HookObject;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\IMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\IObject;
use SRAG\Plugins\Hub2\Object\ITaxonomyAwareObject;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;

/**
 * Class ObjectProcessor
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Sync\Processor
 */
abstract class ObjectSyncProcessor implements IObjectSyncProcessor {

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
	final public function process(IObject $object, IDataTransferObject $dto) {
		$hook = new HookObject($object);
		// We keep the old data if the object is getting deleted, as there is no "real" DTO available, because
		// the data has not been delivered...
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
			case IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED:
				// Updating the ILIAS object is only needed if some properties were changed
				if ($object->computeHashCode() != $object->getHashCode()) {
					$this->implementation->beforeUpdateILIASObject($hook);
					$ilias_object = $this->handleUpdate($dto, $object->getILIASId());
					if ($ilias_object === null) {
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
			case IObject::STATUS_TO_DELETE:
				$this->implementation->beforeDeleteILIASObject($hook);
				$ilias_object = $this->handleDelete($object->getILIASId());
				if ($ilias_object === null) {
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
	 * @param \ilObject|\SRAG\Plugins\Hub2\Sync\Processor\FakeIliasObject $object
	 *
	 * @return int
	 */
	protected function getILIASId($object) {
		if ($object instanceof \ilObjUser || $object instanceof FakeIliasObject
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
	 * Create a new ILIAS object from the given data transfer object.
	 *
	 * @param IDataTransferObject $dto
	 *
	 * @return \ilObject
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
	 * @return \ilObject
	 */
	abstract protected function handleUpdate(IDataTransferObject $dto, $iliasId);


	/**
	 * Delete the corresponding ILIAS object.
	 * Return the deleted ILIAS object or null if the object was not found in ILIAS.
	 *
	 * @param int $iliasId
	 *
	 * @return \ilObject
	 */
	abstract protected function handleDelete($iliasId);
}