<?php namespace SRAG\Hub2\Origin;

use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\IDataTransferObjectFactory;
use SRAG\Hub2\Origin\Config\IOriginConfig;

/**
 * Class AbstractOriginImplementation
 *
 * Any implementation of a origin MUST extend this class.
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
 */
abstract class AbstractOriginImplementation implements IOriginImplementation {

	/**
	 * @var IOriginConfig
	 */
	private $originConfig;
	/**
	 * @var IDataTransferObjectFactory
	 */
	private $factory;
	/**
	 * @var ILog
	 */
	private $originLog;
	/**
	 * @var OriginNotifications
	 */
	private $originNotifications;
	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @param IOriginConfig $config
	 * @param IDataTransferObjectFactory $factory
	 * @param ILog $originLog
	 * @param OriginNotifications $originNotifications
	 */
	public function __construct(IOriginConfig $config,
	                            IDataTransferObjectFactory $factory,
	                            ILog $originLog,
								OriginNotifications $originNotifications) {
		$this->originConfig = $config;
		$this->factory = $factory;
		$this->originLog = $originLog;
		$this->originNotifications = $originNotifications;
	}

	/**
	 * @return IOriginConfig
	 */
	final protected function config() {
		return $this->originConfig;
	}

	/**
	 * @return IDataTransferObjectFactory
	 */
	final protected function factory() {
		return $this->factory;
	}

	/**
	 * @return ILog
	 */
	final protected function log() {
		return $this->originLog;
	}

	/**
	 * @return OriginNotifications
	 */
	final protected function notifications() {
		return $this->originNotifications;
	}
}