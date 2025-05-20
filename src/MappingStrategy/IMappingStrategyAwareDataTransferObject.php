<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\MappingStrategy;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class IMappingStrategyAwareDataTransferObject
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategyAwareDataTransferObject extends IDataTransferObject
{
    public function getMappingStrategy(): IMappingStrategy;

    public function overrideMappingStrategy(IMappingStrategy $strategy): IDataTransferObject;
}
