<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilHub2Plugin;
use srag\Plugins\Hub2\Metadata\IMetadata;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Class IMetadataImplementationFactory
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataImplementationFactory implements IMetadataImplementationFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @inheritdoc
     */
    public function userDefinedField(IMetadata $metadata, int $ilias_id) : IMetadataImplementation
    {
        return new UDF($metadata, $ilias_id);
    }

    /**
     * @inheritdoc
     */
    public function customMetadata(IMetadata $metadata, int $ilias_id) : IMetadataImplementation
    {
        return new CustomMetadata($metadata, $ilias_id);
    }

    /**
     * @inheritdoc
     */
    public function getImplementationForDTO(
        IMetadataAwareDataTransferObject $dto,
        IMetadata $metadata,
        int $ilias_id
    ) : IMetadataImplementation {
        switch (true) {
            case $dto instanceof \srag\Plugins\Hub2\Object\Group\GroupDTO:
            case $dto instanceof \srag\Plugins\Hub2\Object\Course\CourseDTO:
            case $dto instanceof \srag\Plugins\Hub2\Object\Category\CategoryDTO:
            case $dto instanceof \srag\Plugins\Hub2\Object\Session\SessionDTO:
                return $this->customMetadata($metadata, $ilias_id);
            case $dto instanceof \srag\Plugins\Hub2\Object\User\UserDTO:
                return $this->userDefinedField($metadata, $ilias_id);
        }
    }
}
