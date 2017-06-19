<?php namespace SRAG\Hub2\Sync\Processor;

use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Sync\IObjectStatusTransition;


/**
 * Class ObjectProcessor
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync\Processor
 */
abstract class ObjectSyncProcessor implements IObjectSyncProcessor {

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

	public function process(IObject $object) {
		switch ($object->getStatus()) {
			case IObject::STATUS_TO_CREATE:
				$this->origin->implementation()->beforeCreateILIASObject($object);
				$ilias_id = $this->handleCreate($object);
				$object->setILIASId($ilias_id);
				$this->origin->implementation()->afterCreateILIASObject($object);
				break;
			case IObject::STATUS_TO_UPDATE:
			case IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED:
				// Updating the ILIAS object is only needed if some properties were changed
				if ($object->getHashCode() != $object->getHashCodeDatabase()) {
					$this->origin->implementation()->beforeUpdateILIASObject($object);
					$this->handleUpdate($object);
					$this->origin->implementation()->afterUpdateILIASObject($object);
				}
				break;
			case IObject::STATUS_TO_DELETE:
				$this->origin->implementation()->beforeDeleteILIASObject($object);
				$this->handleDelete($object);
				$object->setStatus(IObject::STATUS_DELETED);
				$this->origin->implementation()->afterDeleteILIASObject($object);
				break;
			default:
				throw new HubException("Unrecognized intermediate status '{$object->getStatus()}' while processing {$object}");
		}
		$object->setStatus($this->transition->intermediateToFinal($object));
		$object->setProcessedDate(time());
		$object->save();
	}

	/**
	 * The import ID is set on the ILIAS object.
	 *
	 * @param IObject $object
	 * @return string
	 */
	protected function getImportId(IObject $object) {
		return self::IMPORT_PREFIX . $this->origin->getId() . '_' . $object->getExtId();
	}

	/**
	 * Create the corresponding ILIAS object and return the internal ID in ILIAS (object-ID or ref-ID).
	 *
	 * @param IObject $object
	 * @return int
	 */
	abstract protected function handleCreate(IObject $object);

	/**
	 * Update the corresponding ILIAS object.
	 *
	 * @param IObject $object
	 */
	abstract protected function handleUpdate(IObject $object);

	/**
	 * Delete the corresponding ILIAS object.
	 *
	 * @param IObject $object
	 */
	abstract protected function handleDelete(IObject $object);

}