<?php

namespace SRAG\Plugins\Hub2\Taxonomy;

/**
 * Class TaxonomyFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyFactory implements ITaxonomyFactory {

	/**
	 * @param int $id
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function getDTOWithIliasId(int $id): ITaxonomy {
		return new Taxonomy($id);
	}
}
