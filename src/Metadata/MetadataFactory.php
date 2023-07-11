<?php

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;

/**
 * Class IMetadataFactory
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataFactory implements IMetadataFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @param int $id
     */
    public function getDTOWithIliasId(int $ilias_field_id, int $record_id = IMetadata::DEFAULT_RECORD_ID) : IMetadata
    {
        return new Metadata($ilias_field_id, $record_id);
    }
}
