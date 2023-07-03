<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class srag\Plugins\Hub2\MappingStrategy
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AMappingStrategy implements IMappingStrategy
{
    use DICTrait;
    use Hub2Trait;

    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
}
