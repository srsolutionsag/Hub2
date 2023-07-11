<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use srag\Plugins\Hub2\Metadata\IMetadata;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Class IMetadataImplementationFactory
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataImplementationFactory
{
    public function userDefinedField(IMetadata $metadata, int $ilias_id) : IMetadataImplementation;

    public function customMetadata(IMetadata $metadata, int $ilias_id) : IMetadataImplementation;

    public function getImplementationForDTO(
        IMetadataAwareDataTransferObject $dto,
        IMetadata $metadata,
        int $ilias_id
    ) : IMetadataImplementation;
}
