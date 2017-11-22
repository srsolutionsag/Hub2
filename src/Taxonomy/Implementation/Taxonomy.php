<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

/**
 * Class Taxonomy
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 */
class Taxonomy extends AbstractImplementation implements ITaxonomyImplementation {

	/**
	 * @inheritDoc
	 */
	public function write() {
		$user_id = $this->getIliasId();
		$value = $this->getTaxonomy()->getValue();
		$field_id = $this->getTaxonomy()->getIdentifier();
	}
}
