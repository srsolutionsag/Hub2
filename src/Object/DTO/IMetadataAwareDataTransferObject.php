<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAwareDataTransferObject extends IDataTransferObject
{
    public function addMetadata(IMetadata $IMetadata) : IMetadataAwareDataTransferObject;

    /**
     * @return IMetadata[]
     */
    public function getMetaData() : array;
}
