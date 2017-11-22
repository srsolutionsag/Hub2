<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class CustomTaxonomy
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractImplementation implements ITaxonomyImplementation {

	/**
	 * @var int
	 */
	private $ilias_id;
	/**
	 * @var ITaxonomy
	 */
	private $taxonomy;


	/**
	 * UDF constructor.
	 *
	 * @param $taxonomy
	 */
	public function __construct(ITaxonomy $taxonomy, int $ilias_id) {
		$this->taxonomy = $taxonomy;
		$this->ilias_id = $ilias_id;
	}


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


	/**
	 * @inheritDoc
	 */
	public function getIliasId(): int {
		return $this->ilias_id;
	}
}
