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
 * Class MappingStrategyAwareDataTransferObject
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait MappingStrategyAwareDataTransferObject
{
    /**
     * @var IMappingStrategy
     */
    private $_mapping_strategy;

    public function getMappingStrategy(): IMappingStrategy
    {
        return $this->_mapping_strategy ?: new None();
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function overrideMappingStrategy(IMappingStrategy $strategy): IDataTransferObject
    {
        $this->_mapping_strategy = $strategy;

        return $this;
    }
}
