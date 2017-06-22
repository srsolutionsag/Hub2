<?php namespace SRAG\Hub2\Origin;

use SRAG\Hub2\Object\IDataTransferObjectFactory;
use SRAG\Hub2\Origin\Config\IOriginConfig;
use SRAG\Hub2\Origin\Properties\IOriginProperties;

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
	private $config;

	/**
	 * @var IOriginProperties
	 */
	private $props;

	/**
	 * @var IDataTransferObjectFactory
	 */
	private $factory;

	/**
	 * AbstractOriginImplementation constructor.
	 * @param IOriginConfig $config
	 * @param IOriginProperties $props
	 * @param IDataTransferObjectFactory $factory
	 */
	public function __construct(IOriginConfig $config,
	                            IOriginProperties $props,
	                            IDataTransferObjectFactory $factory) {
		$this->config = $config;
		$this->props = $props;
		$this->factory = $factory;
	}

	/**
	 * @return IOriginConfig
	 */
	protected function config() {
		return $this->config;
	}

	/**
	 * @return IOriginProperties
	 */
	protected function props() {
		return $this->props;
	}

	/**
	 * @return IDataTransferObjectFactory
	 */
	protected function factory() {
		return $this->factory;
	}

}