<?php

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
