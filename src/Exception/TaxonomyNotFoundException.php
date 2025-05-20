<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Exception;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class TaxonomyNotFoundException
 * @package srag\Plugins\Hub2\Exception
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyNotFoundException extends HubException
{
    protected ITaxonomy $taxonomy;

    /**
     * TaxonomyNotFoundException constructor
     */
    public function __construct(ITaxonomy $ta)
    {
        parent::__construct("ILIAS Taxonomy object not found for: {$ta->getTitle()}");
        $this->taxonomy = $ta;
    }

    public function getTaxonomy(): ITaxonomy
    {
        return $this->taxonomy;
    }
}
