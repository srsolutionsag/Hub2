<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class TaxonomyAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomyAwareDataTransferObject
{
    /**
     * @var array
     */
    private $_taxonomies = [];

    /**
     * @inheritdoc
     */
    public function addTaxonomy(ITaxonomy $ITaxonomy): ITaxonomyAwareDataTransferObject
    {
        $this->_taxonomies[] = $ITaxonomy;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTaxonomies(): array
    {
        return $this->_taxonomies;
    }
}
