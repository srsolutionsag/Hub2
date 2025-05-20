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
 * Class MetadataAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait MetadataAwareDataTransferObject
{
    /**
     * @var IMetadata[]
     */
    private $_meta_data = [];

    public function addMetadata(IMetadata $IMetadata): IMetadataAwareDataTransferObject
    {
        $this->_meta_data[$IMetadata->getRecordId() . '_' . $IMetadata->getIdentifier()] = $IMetadata;

        return $this;
    }

    /**
     * @return IMetadata[]
     */
    public function getMetaData(): array
    {
        return $this->_meta_data;
    }
}
