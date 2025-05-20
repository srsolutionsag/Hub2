<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\MappingStrategy;

use ilHub2Plugin;

/**
 * Class srag\Plugins\Hub2\MappingStrategy
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AMappingStrategy implements IMappingStrategy
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
}
