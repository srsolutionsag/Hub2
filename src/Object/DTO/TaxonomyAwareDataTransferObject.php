<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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

    public function addTaxonomy(ITaxonomy $ITaxonomy): ITaxonomyAwareDataTransferObject
    {
        $this->_taxonomies[] = $ITaxonomy;

        return $this;
    }

    public function getTaxonomies(): array
    {
        return $this->_taxonomies;
    }
}
