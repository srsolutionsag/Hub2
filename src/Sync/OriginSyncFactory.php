<?php

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\IObjectRepository;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\OriginImplementationFactory;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\SyncProcessorFactory;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class OriginSyncFactory
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncFactory {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
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
	 *
	 * @throws HubException
	 */
	public function instance(): OriginSync {
		$statusTransition = new ObjectStatusTransition($this->origin->config());

		$originSync = new OriginSync($this->origin, $this->getObjectRepository(), new ObjectFactory($this->origin), $statusTransition);

		return $originSync;
	}


	/**
	 * @param OriginSync $originSync
	 *
	 * @throws HubException
	 */
	public function initImplementation(OriginSync $originSync) {
		$implementationFactory = new OriginImplementationFactory($this->origin);

		$originImplementation = $implementationFactory->instance();

		$originSync->setProcessor($this->getSyncProcessor($this->origin, $originImplementation, $originSync->getStatusTransition()));

		$originSync->setImplementation($originImplementation);
	}


	/**
	 * @return IObjectRepository
	 */
	protected function getObjectRepository() {
		$ucfirst = ucfirst($this->origin->getObjectType());
		$class = "srag\\Plugins\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}Repository";

		return new $class($this->origin);
	}


	/**
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $statusTransition
	 *
	 * @return IObjectSyncProcessor
	 */
	protected function getSyncProcessor(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $statusTransition) {
		$processorFactory = new SyncProcessorFactory($origin, $implementation, $statusTransition);
		$processor = $origin->getObjectType();

		return $processorFactory->$processor();
	}
}
