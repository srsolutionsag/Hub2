<?php

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
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
	 */
	public function instance() {
		$statusTransition = new ObjectStatusTransition($this->origin->config());
		$implementationFactory = new OriginImplementationFactory($this->origin);
		$originImplementation = $implementationFactory->instance();
		$originSync = new OriginSync($this->origin, $this->getObjectRepository(), new ObjectFactory($this->origin), $this->getSyncProcessor($this->origin, $originImplementation, $statusTransition), $statusTransition, $originImplementation);

		return $originSync;
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
