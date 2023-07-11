<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IMappingStrategy
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategy
{
    /**
     * @return int ILIAS ID which will be passed to the Processor.
     * Return 0 if no mapping possible, therefore the Object will be created.
     * Return an existing ILIAS ID which leads to an update of the Object
     * @throws HubException
     */
    public function map(IDataTransferObject $dto) : int;
}
