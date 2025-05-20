<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Taxonomy\Implementation;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Interface ITaxonomyImplementation
 * @package srag\Plugins\Hub2\Taxonomy\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyImplementation
{
    /**
     * Writes the Value in the ILIAS representative
     * @return void
     */
    public function write();

    public function getTaxonomy(): ITaxonomy;

    public function getILIASParentId(): int;
}
