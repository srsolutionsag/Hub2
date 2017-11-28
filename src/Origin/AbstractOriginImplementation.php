<?php

namespace SRAG\Plugins\Hub2\Origin;

use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Metadata\IMetadataFactory;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;
use SRAG\Plugins\Hub2\Taxonomy\ITaxonomyFactory;

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
	 * @var \SRAG\Plugins\Hub2\Taxonomy\ITaxonomyFactory
	 */
	private $taxonomyFactory;
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
	 * AbstractOriginImplementation constructor.
	 *
	 * @param \SRAG\Plugins\Hub2\Origin\Config\IOriginConfig           $config
	 * @param \SRAG\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory $factory
	 * @param \SRAG\Plugins\Hub2\Log\ILog                              $originLog
	 * @param \SRAG\Plugins\Hub2\Notification\OriginNotifications      $originNotifications
	 * @param \SRAG\Plugins\Hub2\Metadata\IMetadataFactory             $metadataFactory
	 * @param \SRAG\Plugins\Hub2\Taxonomy\ITaxonomyFactory             $taxonomyFactory
	 */
	public function __construct(IOriginConfig $config, IDataTransferObjectFactory $factory, ILog $originLog, OriginNotifications $originNotifications, IMetadataFactory $metadataFactory, ITaxonomyFactory $taxonomyFactory) {
		$this->originConfig = $config;
		$this->factory = $factory;
		$this->originLog = $originLog;
		$this->originNotifications = $originNotifications;
		$this->metadataFactory = $metadataFactory;
		$this->taxonomyFactory = $taxonomyFactory;
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
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomyFactory
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
}