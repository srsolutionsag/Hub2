<?php namespace SRAG\Hub2\Origin;
use SRAG\Hub2\Config\IHubConfig;
use SRAG\Hub2\Exception\HubException;

/**
 * Class OriginImplementationTemplateGenerator
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
 */
class OriginImplementationTemplateGenerator {
	/**
	 * @var IHubConfig
	 */
	private $hubConfig;

	/**
	 * OriginImplementationTemplateGenerator constructor.
	 * @param IHubConfig $hubConfig
	 */
	public function __construct(IHubConfig $hubConfig) {
		$this->hubConfig = $hubConfig;
	}

	/**
	 * Create the implementation class file from a given template at the correct location
	 * based on the hub config.
	 *
	 * @param IOrigin $origin
	 * @return bool False if file exists, true if created
	 * @throws HubException
	 */
	public function create(IOrigin $origin) {
		$classFile = $this->getClassFilePath($origin);
		if (is_file($classFile)) {
			return false;
		}
		$path = $this->getPath($origin);
		if (!is_dir($path)) {
			if (!\ilUtil::makeDirParents($path)) {
				throw new HubException("Could not create directory: $path");
			};
		}
		$template = file_get_contents(__DIR__ . '/OriginImplementationTemplate.tpl');
		if ($template === false) {
			throw new HubException("Could not load template: $template");
		}
		$className = $origin->getImplementationClassName();
		$content = str_replace('[[CLASSNAME]]', $className, $template);
		$result = file_put_contents($classFile, $content);
		if ($result === false) {
			throw new HubException("Unable to create template for origin implementation");
		}
		return true;
	}

	/**
	 * @param IOrigin $origin
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
	 * @return string
	 */
	protected function getPath(IOrigin $origin) {
		$basePath = rtrim($this->hubConfig->getOriginImplementationsPath(), '/') . '/';
		$path = $basePath . $origin->getObjectType() . '/';
		return $path;
	}

}