<?php

namespace srag\Plugins\Hub2\Origin\Config;

use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyFactory;
use srag\Plugins\Hub2\Metadata\MetadataFactory;
use srag\Plugins\Hub2\Object\DTO\DataTransferObjectFactory;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Taxonomy\TaxonomyFactory;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class OriginImplementationFactory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Origin\Config
 */
class OriginImplementationFactory {

	use DICTrait;
	use Hub2Trait;
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
	 * @return IOriginImplementation
	 * @throws HubException
	 */
	public function instance() {
		$basePath = rtrim(ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH), '/') . '/';
		$path = $basePath . $this->origin->getObjectType() . '/';
		$className = $this->origin->getImplementationClassName();
		$namespace = $this->origin->getImplementationNamespace();
		$classFile = $path . $className . '.php';
		if (!is_file($classFile)) {
			throw new HubException("Origin implementation class file does not exist, should be at: $classFile");
		}
		require_once $classFile;
		$class = rtrim($namespace, "\\") . "\\" . $className;
		if (!class_exists($class)) {
			throw new HubException("Origin implementation namespace\\class does not exist, should be: $class");
		}
		$instance = new $class($this->origin->config(), new DataTransferObjectFactory(), new MetadataFactory(), new TaxonomyFactory(), new MappingStrategyFactory(), $this->origin);

		return $instance;
	}
}
