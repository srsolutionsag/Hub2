<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;

/**
 * Interface ITaxonomySyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomySyncProcessor
{
    /**
     * @return void
     */
    public function handleTaxonomies(
        ITaxonomyAwareDataTransferObject $dto,
        ITaxonomyAwareObject $iobject,
        ilObject $ilias_object
    );
}
