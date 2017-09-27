<?php
namespace SRAG\Hub2\Sync;

use SRAG\Hub2\Config\HubConfig;
use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Log\OriginLog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\IObjectRepository;
use SRAG\Hub2\Object\ObjectFactory;
use SRAG\Hub2\Origin\Config\OriginImplementationFactory;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Sync\Processor\IObjectSyncProcessor;
use SRAG\Hub2\Sync\Processor\SyncProcessorFactory;

/**
 * Class OriginSyncFactory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync
 */
class OriginSyncFactory {

	/**
	 * @var IOrigin
	 */
	protected $origin;


	/**
	 * @param IOrigin $origin
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
	}


	/**
	 * @return OriginSync
	 */
	public function instance() {
		$statusTransition = new ObjectStatusTransition($this->origin->config());
		$originLog = new OriginLog($this->origin);
		$originNotifications = new OriginNotifications();
		$implementationFactory = new OriginImplementationFactory(new HubConfig(), $this->origin, $originLog, $originNotifications);
		$originImplementation = $implementationFactory->instance();
		$originSync = new OriginSync($this->origin, $this->getObjectRepository(), new ObjectFactory($this->origin), $this->getSyncProcessor($this->origin, $originImplementation, $statusTransition, $originLog, $originNotifications), $statusTransition, $originImplementation, $originNotifications);

		return $originSync;
	}


	/**
	 * @return IObjectRepository
	 */
	protected function getObjectRepository() {
		$ucfirst = ucfirst($this->origin->getObjectType());
		$class = "SRAG\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}Repository";

		return new $class($this->origin);
	}


	/**
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $statusTransition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 *
	 * @return IObjectSyncProcessor
	 */
	protected function getSyncProcessor(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $statusTransition, ILog $originLog, OriginNotifications $originNotifications) {
		$processorFactory = new SyncProcessorFactory($origin, $implementation, $statusTransition, $originLog, $originNotifications);
		$processor = $origin->getObjectType();

		return $processorFactory->$processor();
	}
}