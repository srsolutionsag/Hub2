<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;

/**
 * Interface IMetadataSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataSyncProcessor
{
    /**
     * @return void
     */
    public function handleMetadata(
        IMetadataAwareDataTransferObject $dto,
        IMetadataAwareObject $iobject,
        ilObject $ilias_object
    );
}
