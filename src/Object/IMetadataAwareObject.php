<?php

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAwareObject extends IObject
{
    /**
     * @return IMetadata[]
     */
    public function getMetaData(): array;

    /**
     * @param IMetadata[] $metadata
     * @return void
     */
    public function setMetaData(array $metadata);
}
