<?php namespace SRAG\Hub2\Origin;

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
	private $config;

	/**
	 * @var IDataTransferObjectFactory
	 */
	private $factory;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @param IOriginConfig $config
	 * @param IDataTransferObjectFactory $factory
	 */
	public function __construct(IOriginConfig $config,
	                            IDataTransferObjectFactory $factory) {
		$this->config = $config;
		$this->factory = $factory;
	}

	/**
	 * @return IOriginConfig
	 */
	protected function config() {
		return $this->config;
	}

	/**
	 * @return IDataTransferObjectFactory
	 */
	protected function factory() {
		return $this->factory;
	}

}