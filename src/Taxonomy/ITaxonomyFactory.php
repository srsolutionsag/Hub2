<?php

namespace SRAG\Plugins\Hub2\Taxonomy;

/**
 * Class ITaxonomyFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyFactory {

	/**
	 * @param int $id
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function getDTOWithIliasId(int $id): ITaxonomy;
}
