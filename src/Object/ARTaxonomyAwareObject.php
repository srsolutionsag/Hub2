<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class ARTaxonomyAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARTaxonomyAwareObject
{
    /**
     * @var array
     * @db_has_field    true
     * @db_fieldtype    clob
     */
    protected $taxonomies = [];

    /**
     * @return ITaxonomy[]
     */
    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    /**
     * @param ITaxonomy[] $taxonomies
     */
    public function setTaxonomies(array $taxonomies): void
    {
        $this->taxonomies = $taxonomies;
    }
}
