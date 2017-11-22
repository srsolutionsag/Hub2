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
	 * @param int                                   $ilias_id
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\Implementation\ITaxonomyImplementation
	 */
	public function taxanomy(ITaxonomy $Taxonomy, int $ilias_id): ITaxonomyImplementation;
}

