<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class srag\Plugins\Hub2\MappingStrategy
 *
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AMappingStrategy implements IMappingStrategy {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
}
