<?php namespace SRAG\Hub2\Origin\Config;

use SRAG\Hub2\Config\IHubConfig;
use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\DataTransferObjectFactory;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;

/**
 * Class OriginImplementationFactory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Config
 */
class OriginImplementationFactory {

	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var IHubConfig
	 */
	protected $hubConfig;
	/**
	 * @var ILog
	 */
	protected $originLog;
	/**
	 * @var OriginNotifications
	 */
	protected $originNotifications;


	/**
	 * @param IHubConfig          $hubConfig
	 * @param IOrigin             $origin
	 * @param ILog                $originLog
	 * @param OriginNotifications $originNotifications
	 */
	public function __construct(IHubConfig $hubConfig, IOrigin $origin, ILog $originLog, OriginNotifications $originNotifications) {
		$this->hubConfig = $hubConfig;
		$this->origin = $origin;
		$this->originLog = $originLog;
		$this->originNotifications = $originNotifications;
	}


	/**
	 * @return IOriginImplementation
	 * @throws HubException
	 */
	public function instance() {
		$basePath = rtrim($this->hubConfig->getOriginImplementationsPath(), '/') . '/';
		$path = $basePath . $this->origin->getObjectType() . '/';
		$className = $this->origin->getImplementationClassName();
		$classFile = $path . $className . '.php';
		if (!is_file($classFile)) {
			throw new HubException("Origin implementation class file does not exist, should be at: $classFile");
		}
		require_once($classFile);
		$class = "SRAG\\Hub2\\Origin\\" . $className;
		$instance = new $class($this->origin->config(), new DataTransferObjectFactory(), $this->originLog, $this->originNotifications);

		return $instance;
	}
}