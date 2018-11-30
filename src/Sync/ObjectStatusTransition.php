<?php

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class ObjectStatusTransition
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @deprecated
 */
class ObjectStatusTransition implements IObjectStatusTransition {

	use DICTrait;
	use Hub2Trait;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var array
	 *
	 * @deprecated
	 */
	protected static $final = [
		IObject::STATUS_NEW,
		IObject::STATUS_CREATED,
		IObject::STATUS_UPDATED,
		IObject::STATUS_OUTDATED,
		IObject::STATUS_IGNORED,
	];
	/**
	 * @var IOriginConfig
	 *
	 * @deprecated
	 */
	protected $config;


	/**
	 * @param IOriginConfig $config
	 *
	 * @deprecated
	 */
	public function __construct(IOriginConfig $config) {
		$this->config = $config;
	}


	/**
	 * @inheritdoc
	 *
	 * @deprecated
	 */
	public function finalToIntermediate(IObject $object): int {
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

			case IObject::STATUS_OUTDATED:
				return IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED;

			case IObject::STATUS_IGNORED:
				// Either create or update the ILIAS object
				return ($object->getILIASId()) ? IObject::STATUS_TO_UPDATE : IObject::STATUS_TO_CREATE;
		}

		throw new HubException(sprintf("Could not transition to intermediate state from state %s", $object->getStatus()));
	}


	/**
	 * @inheritdoc
	 *
	 * @deprecated
	 */
	public function intermediateToFinal(IObject $object): int {
		if ($this->isFinal($object->getStatus())) {
			return $object->getStatus();
		}

		switch ($object->getStatus()) {
			case IObject::STATUS_TO_CREATE:
				return IObject::STATUS_CREATED;

			case IObject::STATUS_TO_UPDATE:
			case IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED:
				return IObject::STATUS_UPDATED;

			case IObject::STATUS_TO_OUTDATED:
				return IObject::STATUS_OUTDATED;

			case IObject::STATUS_NOTHING_TO_UPDATE:
				return IObject::STATUS_IGNORED;
		}

		throw new HubException(sprintf("Could not transition to final state from state %s", $object->getStatus()));
	}


	/**
	 * @param int $status
	 *
	 * @return bool
	 *
	 * @deprecated
	 */
	protected function isFinal(int $status): bool {
		return in_array($status, self::$final);
	}
}
