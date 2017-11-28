<?php

namespace SRAG\Plugins\Hub2\Object;

/**
 * Interface ITaxonomyAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 */
interface ITaxonomyAwareObject extends IObject {

	/**
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy[]
	 */
	public function getTaxonomies(): array;


	/**
	 * @param \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy[] $taxonomies
	 *
	 * @return void
	 */
	public function setTaxonomies(array $taxonomies);
}