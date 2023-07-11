<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Sync\IDataTransferObjectSort;
use Throwable;

/**
 * Interface ObjectProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObjectSyncProcessor
{
    // This prefix MUST be used by the processors when setting the import ID on the ILIAS objects
    public const IMPORT_PREFIX = 'srhub_';

    /**
     * Process the given hub object and its corresponding DTO:
     * 1. Depending on the status: Create, Update or Delete corresponding ILIAS object
     * 2. Execute other actions based on the configuration of the origin.
     * 3. Pass the DTO to the hooks of the origin
     * @param bool $force Update all Objects without Hash comparison
     * @throws Throwable
     */
    public function process(IObject $hub_object, IDataTransferObject $dto, bool $force = false);

    /**
     * Set sort levels
     * @param IDataTransferObjectSort[] $sort_dtos
     * @return bool Should sort?
     */
    public function handleSort(array $sort_dtos) : bool;
}
