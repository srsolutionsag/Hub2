<?php

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Interface ITaxonomyAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyAwareObject extends IObject
{
    /**
     * @return ITaxonomy[]
     */
    public function getTaxonomies(): array;

    /**
     * @param ITaxonomy[] $taxonomies
     * @return void
     */
    public function setTaxonomies(array $taxonomies);
}
