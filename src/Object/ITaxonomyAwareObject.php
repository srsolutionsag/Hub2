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
