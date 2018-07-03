<?php

namespace SRAG\Plugins\Hub2\Object\DTO;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class TaxonomyAwareDataTransferObject
 *
 * @package SRAG\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomyAwareDataTransferObject {

	/**
	 * @var array
	 */
	private $_taxonomies = [];


	/**
	 * @inheritDoc
	 */
	public function addTaxonomy(ITaxonomy $ITaxonomy): ITaxonomyAwareDataTransferObject {
		$this->_taxonomies[] = $ITaxonomy;

		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function getTaxonomies(): array {
		return $this->_taxonomies;
	}
}
