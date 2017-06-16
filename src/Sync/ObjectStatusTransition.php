<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync;
use SRAG\ILIAS\Plugins\Exception\HubException;
use SRAG\ILIAS\Plugins\Hub2\Object\IObject;
use SRAG\ILIAS\Plugins\Hub2\Origin\IOrigin;

/**
 * Class ObjectStatusTransition
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Sync
 */
class ObjectStatusTransition implements IObjectStatusTransition {

	/**
	 * @var array
	 */
	protected static $final = [
		IObject::STATUS_NEW,
		IObject::STATUS_CREATED,
		IObject::STATUS_UPDATED,
		IObject::STATUS_DELETED,
		IObject::STATUS_IGNORED,
	];

	/**
	 * @var IOrigin
	 */
	protected $origin;

	/**
	 * Status constructor.
	 * @param IOrigin $origin
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
	}


	/**
	 * @inheritdoc
	 */
	public function finalToIntermediate(IObject $object) {
		if (!$this->isFinal($object->getStatus())) {
			return $object->getStatus();
		}
		$active_period = $this->origin->config()->getActivePeriod();
		if ($active_period && ($object->getPeriod() != $active_period)) {
			return IObject::STATUS_IGNORED;
		}
		switch ($object->getStatus()) {
			case IObject::STATUS_NEW:
				return IObject::STATUS_TO_CREATE;
			case IObject::STATUS_CREATED:
			case IObject::STATUS_UPDATED:
				return IObject::STATUS_TO_UPDATE;
			case IObject::STATUS_DELETED:
				return IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED;
			case IObject::STATUS_TO_UPDATE:
			case IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED:
				return IObject::STATUS_UPDATED;
			case IObject::STATUS_IGNORED:
				// Either create or update the ILIAS object
				return ($object->getILIASId()) ? IObject::STATUS_TO_UPDATE : IObject::STATUS_TO_CREATE;
		}
		throw new HubException(sprintf("Could not transition to intermediate state from state %s", $object->getStatus()));
	}

	/**
	 * @inheritdoc
	 */
	public function intermediateToFinal(IObject $object) {
		if ($this->isFinal($object->getStatus())) {
			return $object->getStatus();
		}
		switch ($object->getStatus()) {
			case IObject::STATUS_TO_CREATE:
				return IObject::STATUS_CREATED;
			case IObject::STATUS_TO_UPDATE:
			case IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED:
				return IObject::STATUS_UPDATED;
			case IObject::STATUS_TO_DELETE:
				return IObject::STATUS_DELETED;
		}
		throw new HubException(sprintf("Could not transition to final state from state %s", $object->getStatus()));
	}

	/**
	 * @param int $status
	 * @return bool
	 */
	protected function isFinal($status) {
		return in_array($status, self::$final);
	}

}