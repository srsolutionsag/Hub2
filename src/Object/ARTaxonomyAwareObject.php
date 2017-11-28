<?php

namespace SRAG\Plugins\Hub2\Object;

/**
 * Class ARTaxonomyAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 */
trait ARTaxonomyAwareObject {

	/**
	 * @var array
	 *
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 */
	protected $taxonomies = array();


	/**
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy[]
	 */
	public function getTaxonomies(): array {
		return $this->taxonomies;
	}


	/**
	 * @param \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy[] $taxonomies
	 */
	public function setTaxonomies(array $taxonomies) {
		$this->taxonomies = $taxonomies;
	}
}