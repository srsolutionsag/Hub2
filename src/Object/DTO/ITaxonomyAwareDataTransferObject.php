<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Interface ITaxonomyAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyAwareDataTransferObject extends IDataTransferObject
{
    public function addTaxonomy(ITaxonomy $ITaxonomy) : ITaxonomyAwareDataTransferObject;

    /**
     * @return ITaxonomy[]
     */
    public function getTaxonomies() : array;
}
