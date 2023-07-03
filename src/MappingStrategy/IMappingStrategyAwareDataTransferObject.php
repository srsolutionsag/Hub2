<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class IMappingStrategyAwareDataTransferObject
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategyAwareDataTransferObject extends IDataTransferObject
{
    /**
     * @inheritdoc
     */
    public function getMappingStrategy(): IMappingStrategy;

    /**
     * @inheritdoc
     */
    public function overrideMappingStrategy(IMappingStrategy $strategy): IDataTransferObject;
}
