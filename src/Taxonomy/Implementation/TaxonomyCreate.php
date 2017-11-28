<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

/**
 * Class TaxonomyCreate
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 */
class TaxonomyCreate extends AbstractTaxonomy implements ITaxonomyImplementation {

	/**
	 * @inheritDoc
	 */
	public function write() {
		$nodes = $this->getTaxonomy()->getNodes();

		//
	}
}
