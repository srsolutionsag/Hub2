<?php

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

    /**
     * @inheritdoc
     */
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
