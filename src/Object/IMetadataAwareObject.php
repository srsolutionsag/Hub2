<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
