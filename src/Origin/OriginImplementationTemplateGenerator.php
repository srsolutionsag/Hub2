<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use ilUtil;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;

/**
 * Class OriginImplementationTemplateGenerator
 *
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginImplementationTemplateGenerator {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * OriginImplementationTemplateGenerator constructor
	 */
	public function __construct() {

	}


	/**
	 * Create the implementation class file from a given template at the correct location
	 * based on the hub config.
	 *
	 * @param IOrigin $origin
	 *
	 * @return bool False if file exists, true if created
	 * @throws HubException
	 */
	public function create(IOrigin $origin) {
		$classFile = $this->getClassFilePath($origin);
		if ($this->classFileExists($origin)) {
			return false;
		}
		$path = $this->getPath($origin);
		if (!is_dir($path)) {
			if (!ilUtil::makeDirParents($path)) {
				throw new HubException("Could not create directory: $path");
			};
		}
		$template = file_get_contents(__DIR__ . '/OriginImplementationTemplate.tpl');
		if ($template === false) {
			throw new HubException("Could not load template: $template");
		}
		$className = $origin->getImplementationClassName();
		$content = str_replace('[[CLASSNAME]]', $className, $template);
		// TODO: Insert [[NAMESPACE]] with $origin->getImplementationNamespace()
		$result = file_put_contents($classFile, $content);
		if ($result === false) {
			throw new HubException("Unable to create template for origin implementation");
		}

		return true;
	}


	public function classFileExists(IOrigin $origin) {
		$classFile = $this->getClassFilePath($origin);

		return is_file($classFile);
	}


	/**
	 * @param IOrigin $origin
	 *
	 * @return string
	 */
	public function getClassFilePath(IOrigin $origin) {
		$path = $this->getPath($origin);
		$className = $origin->getImplementationClassName();
		$classFile = $path . $className . '.php';

		return $classFile;
	}


	/**
	 * @param IOrigin $origin
	 *
	 * @return string
	 */
	protected function getPath(IOrigin $origin) {
		$basePath = rtrim(ArConfig::getOriginImplementationsPath(), '/') . '/';
		$path = $basePath . $origin->getObjectType() . '/';

		return $path;
	}
}
