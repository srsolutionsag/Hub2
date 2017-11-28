<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class AbstractTaxonomy
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 */
abstract class AbstractTaxonomy implements ITaxonomyImplementation {

	/**
	 * @var ITaxonomy
	 */
	protected $taxonomy;


	/**
	 * Taxonomy constructor.
	 *
	 * @param ITaxonomy $taxonomy
	 */
	public function __construct(ITaxonomy $taxonomy) { $this->taxonomy = $taxonomy; }


	/**
	 * @inheritDoc
	 */
	abstract public function write();


	/**
	 * @inheritDoc
	 */
	public function getTaxonomy(): ITaxonomy {
		return $this->taxonomy;
	}
}
