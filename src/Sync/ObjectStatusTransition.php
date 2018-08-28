<?php

namespace SRAG\Plugins\Hub2\Sync;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Object\IObject;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Class ObjectStatusTransition
 *
 * @package SRAG\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ObjectStatusTransition implements IObjectStatusTransition {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
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
	 * @var IOriginConfig
	 */
	protected $config;


	/**
	 * @param IOriginConfig $config
	 */
	public function __construct(IOriginConfig $config) {
		$this->config = $config;
	}


	/**
	 * @inheritdoc
	 */
	public function finalToIntermediate(IObject $object) {
		if (!$this->isFinal($object->getStatus())) {
			return $object->getStatus();
		}
		// If the config has defined an active period and the period of the object does not match,
		// we set the status to IGNORED. The sync won't process this object anymore.
		// If at any time there is no active period defined OR the object matches the period again,
		// the status will be set to TO_UPDATE or TO_CREATE again.
		$active_period = $this->config->getActivePeriod();
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
			case IObject::STATUS_NOTHING_TO_UPDATE:
				return IObject::STATUS_IGNORED;
		}
		throw new HubException(sprintf("Could not transition to final state from state %s", $object->getStatus()));
	}


	/**
	 * @param int $status
	 *
	 * @return bool
	 */
	protected function isFinal($status) {
		return in_array($status, self::$final);
	}
}
