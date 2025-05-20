<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAwareDataTransferObject extends IDataTransferObject
{
    public function addMetadata(IMetadata $IMetadata): IMetadataAwareDataTransferObject;

    /**
     * @return IMetadata[]
     */
    public function getMetaData(): array;
}
