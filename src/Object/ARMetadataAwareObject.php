<?php

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class ARMetadataAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARMetadataAwareObject
{
    /**
     * @var array
     * @db_has_field    true
     * @db_fieldtype    clob
     */
    protected $meta_data = [];

    /**
     * @return IMetadata[]
     */
    public function getMetaData() : array
    {
        return is_array($this->meta_data) ? $this->meta_data : [];
    }

    public function setMetaData(array $meta_data) : void
    {
        $this->meta_data = $meta_data;
    }
}
