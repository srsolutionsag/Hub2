<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Interface ITaxonomyImplementation
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyImplementation {

	/**
	 * Writes the Value in the ILIAS representative
	 *
	 * @return void
	 */
	public function write();


	/**
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function getTaxonomy(): ITaxonomy;


	/**
	 * @return int
	 */
	public function getILIASParentId():int;
}