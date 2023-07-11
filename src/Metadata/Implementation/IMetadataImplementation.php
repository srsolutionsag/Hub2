<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataImplementation
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataImplementation
{
    /**
     * Writes the Value in the ILIAS representative (UDF od Custom MD)
     * @return void
     */
    public function write();

    public function getMetadata() : IMetadata;

    public function getIliasId() : int;
}
