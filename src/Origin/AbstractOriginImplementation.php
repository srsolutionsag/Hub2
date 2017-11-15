<?php
namespace SRAG\Plugins\Hub2\Origin;

use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Metadata\IMetadataFactory;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Class AbstractOriginImplementation
 *
 * Any implementation of a origin MUST extend this class.
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Origin
 */
abstract class AbstractOriginImplementation implements IOriginImplementation {

	/**
	 * @var \SRAG\Plugins\Hub2\Metadata\IMetadataFactory
	 */
	private $metadataFactory;
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
	 * @param IOriginConfig              $config
	 * @param IDataTransferObjectFactory $factory
	 * @param ILog                       $originLog
	 * @param OriginNotifications        $originNotifications
	 */
	public function __construct(IOriginConfig $config, IDataTransferObjectFactory $factory, ILog $originLog, OriginNotifications $originNotifications, IMetadataFactory $metadataFactory) {
		$this->originConfig = $config;
		$this->factory = $factory;
		$this->originLog = $originLog;
		$this->originNotifications = $originNotifications;
		$this->metadataFactory = $metadataFactory;
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
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadataFactory
	 */
	final protected function metadata() {
		return $this->metadataFactory;
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