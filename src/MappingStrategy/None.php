<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class None
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class None extends AMappingStrategy implements IMappingStrategy
{
    /**
     * @inheritdoc
     */
    public function map(IDataTransferObject $dto): int
    {
        return 0;
    }
}
