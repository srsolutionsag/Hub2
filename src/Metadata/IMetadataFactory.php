<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Metadata;

/**
 * Class IMetadataFactory
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataFactory
{
    public function getDTOWithIliasId(int $ilias_field_id, int $record_id = IMetadata::DEFAULT_RECORD_ID): IMetadata;
}
