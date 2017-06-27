<?php namespace SRAG\Hub2\Sync\Processor;

use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Exception\ILIASObjectNotFoundException;
use SRAG\Hub2\Object\HookObject;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\IDataTransferObject;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Sync\IObjectStatusTransition;


/**
 * Class ObjectProcessor
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
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
	 * @param IOrigin $origin
	 * @param IObjectStatusTransition $transition
	 */
	public function __construct(IOrigin $origin, IObjectStatusTransition $transition) {
		$this->origin = $origin;
		$this->transition = $transition;
	}

	final public function process(IObject $object, IDataTransferObject $dto) {
		$hook = new HookObject($object);
		switch ($object->getStatus()) {
			case IObject::STATUS_TO_CREATE:
				$this->origin->implementation()->beforeCreateILIASObject($hook);
				$ilias_object = $this->handleCreate($dto);
				$object->setILIASId($this->getILIASId($ilias_object));
				$this->origin->implementation()->afterCreateILIASObject($hook->withILIASObject($ilias_object));
				break;
			case IObject::STATUS_TO_UPDATE:
			case IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED:
				// Updating the ILIAS object is only needed if some properties were changed
				if ($object->computeHashCode() != $object->getHashCode()) {
					$this->origin->implementation()->beforeUpdateILIASObject($hook);
					$ilias_object = $this->handleUpdate($dto, $object->getILIASId());
					if ($ilias_object === null) {
						throw new ILIASObjectNotFoundException($object);
					}
					$this->origin->implementation()->afterUpdateILIASObject($hook->withILIASObject($ilias_object));
				}
				break;
			case IObject::STATUS_TO_DELETE:
				$this->origin->implementation()->beforeDeleteILIASObject($hook);
				$ilias_object = $this->handleDelete($object->getILIASId());
				if ($ilias_object === null) {
					throw new ILIASObjectNotFoundException($object);
				}
				$this->origin->implementation()->afterDeleteILIASObject($hook->withILIASObject($ilias_object));
				break;
			default:
				throw new HubException("Unrecognized intermediate status '{$object->getStatus()}' while processing {$object}");
		}
		$object->setStatus($this->transition->intermediateToFinal($object));
		$object->setData($dto->getData());
		$object->setProcessedDate(time());
		$object->save();
	}

	/**
	 * @param \ilObject $object
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
	 * @return string
	 */
	protected function getImportId(IDataTransferObject $object) {
		return self::IMPORT_PREFIX . $this->origin->getId() . '_' . $object->getExtId();
	}

	/**
	 * Create a new ILIAS object from the given data transfer object.
	 *
	 * @param IDataTransferObject $object
	 * @return \ilObject
	 */
	abstract protected function handleCreate(IDataTransferObject $object);

	/**
	 * Update the corresponding ILIAS object.
	 * Return the processed ILIAS object or null if the object was not found, e.g. it is deleted in ILIAS.
	 *
	 * @param IDataTransferObject $object
	 * @param int $ilias_id
	 * @return \ilObject
	 */
	abstract protected function handleUpdate(IDataTransferObject $object, $ilias_id);

	/**
	 * Delete the corresponding ILIAS object.
	 * Return the deleted ILIAS object or null if the object was not found in ILIAS.
	 *
	 * @param int $ilias_id
	 * @return \ilObject
	 */
	abstract protected function handleDelete($ilias_id);

}