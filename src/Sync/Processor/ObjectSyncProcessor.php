<?php namespace SRAG\Hub2\Sync\Processor;

use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Exception\ILIASObjectNotFoundException;
use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\HookObject;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\IDataTransferObject;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Sync\IObjectStatusTransition;

/**
 * Class ObjectProcessor
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync\Processor
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
		}
		switch ($object->getStatus()) {
			case IObject::STATUS_TO_CREATE:
				$this->implementation->beforeCreateILIASObject($hook);
				$ilias_object = $this->handleCreate($dto);
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
	 * @param \ilObject $object
	 *
	 * @return int
	 */
	protected function getILIASId(\ilObject $object) {
		if ($object instanceof \ilObjUser) {
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
	 * @param IDataTransferObject $object
	 *
	 * @return \ilObject
	 */
	abstract protected function handleCreate(IDataTransferObject $object);


	/**
	 * Update the corresponding ILIAS object.
	 * Return the processed ILIAS object or null if the object was not found, e.g. it is deleted in
	 * ILIAS.
	 *
	 * @param IDataTransferObject $object
	 * @param int                 $iliasId
	 *
	 * @return \ilObject
	 */
	abstract protected function handleUpdate(IDataTransferObject $object, $iliasId);


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