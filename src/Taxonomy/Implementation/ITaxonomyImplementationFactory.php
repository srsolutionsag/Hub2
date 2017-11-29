<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class ITaxonomyImplementationFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyImplementationFactory {

	/**
	 * @param \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy $Taxonomy
	 * @param \ilObject                             $ilias_object
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\Implementation\ITaxonomyImplementation
	 */
	public function taxonomy(ITaxonomy $Taxonomy, \ilObject $ilias_object): ITaxonomyImplementation;
}

