<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
