<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\SessionMembership;

use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class SessionMembershipSyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipSyncProcessor extends ObjectSyncProcessor implements ISessionMembershipSyncProcessor {

	/**
	 * @var \SRAG\Plugins\Hub2\Origin\Properties\SessionMembershipOriginProperties
	 */
	private $props;
	/**
	 * @var \SRAG\Plugins\Hub2\Origin\Config\SessionMembershipOriginConfig
	 */
	private $config;
	/**
	 * @var array
	 */
	protected static $properties = array();


	/**
	 * @param IOrigin                 $origin
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
	public static function getProperties() {
		return self::$properties;
	}


	protected function handleCreate(IDataTransferObject $dto) {
		/** @var \SRAG\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO $dto */
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/** @var \SRAG\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO $dto */
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {

	}
}