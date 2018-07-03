<?php

namespace SRAG\Plugins\Hub2\Object\DTO;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Interface ITaxonomyAwareDataTransferObject
 *
 * @package SRAG\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyAwareDataTransferObject extends IDataTransferObject {

	/**
	 * @param ITaxonomy $ITaxonomy
	 *
	 * @return ITaxonomyAwareDataTransferObject
	 */
	public function addTaxonomy(ITaxonomy $ITaxonomy): ITaxonomyAwareDataTransferObject;


	/**
	 * @return ITaxonomy[]
	 */
	public function getTaxonomies(): array;
}
