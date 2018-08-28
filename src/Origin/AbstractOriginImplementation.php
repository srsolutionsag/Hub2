<?php

namespace SRAG\Plugins\Hub2\Origin;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\MappingStrategy\IMappingStrategyFactory;
use SRAG\Plugins\Hub2\Metadata\IMetadataFactory;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory;
use SRAG\Plugins\Hub2\Object\HookObject;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;
use SRAG\Plugins\Hub2\Taxonomy\ITaxonomyFactory;

/**
 * Class AbstractOriginImplementation
 *
 * Any implementation of a origin MUST extend this class.
 *
 * @package SRAG\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractOriginImplementation implements IOriginImplementation {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IMappingStrategyFactory
	 */
	private $mapping_strategy_factory;
	/**
	 * @var ITaxonomyFactory
	 */
	private $taxonomyFactory;
	/**
	 * @var IMetadataFactory
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
	 * AbstractOriginImplementation constructor.
	 *
	 * @param IOriginConfig              $config
	 * @param IDataTransferObjectFactory $factory
	 * @param ILog                       $originLog
	 * @param OriginNotifications        $originNotifications
	 * @param IMetadataFactory           $metadataFactory
	 * @param ITaxonomyFactory           $taxonomyFactory
	 */
	public function __construct(IOriginConfig $config, IDataTransferObjectFactory $factory, ILog $originLog, OriginNotifications $originNotifications, IMetadataFactory $metadataFactory, ITaxonomyFactory $taxonomyFactory, IMappingStrategyFactory $mapping_strategy) {
		$this->originConfig = $config;
		$this->factory = $factory;
		$this->originLog = $originLog;
		$this->originNotifications = $originNotifications;
		$this->metadataFactory = $metadataFactory;
		$this->taxonomyFactory = $taxonomyFactory;
		$this->mapping_strategy_factory = $mapping_strategy;
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
	 * @return IMappingStrategyFactory
	 */
	final protected function mapping(): IMappingStrategyFactory {
		return $this->mapping_strategy_factory;
	}


	/**
	 * @return IMetadataFactory
	 */
	final protected function metadata() {
		return $this->metadataFactory;
	}


	/**
	 * @return ITaxonomyFactory
	 */
	final protected function taxonomy() {
		return $this->taxonomyFactory;
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

	// HOOKS


	/**
	 * @inheritDoc
	 */
	public function overrideStatus(HookObject $hook) {
		// TODO: Implement overrideStatus() method.
	}
}
