<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\DTO;

/**
 * Interface ITaxonomyAndMetadataAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyAndMetadataAwareDataTransferObject extends IMetadataAwareDataTransferObject,
    ITaxonomyAwareDataTransferObject
{
}
