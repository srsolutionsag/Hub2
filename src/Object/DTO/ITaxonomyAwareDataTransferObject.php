<?php

namespace SRAG\Plugins\Hub2\Object\DTO;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Interface ITaxonomyAwareDataTransferObject
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyAwareDataTransferObject extends IDataTransferObject {

	/**
	 * @param \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy $ITaxonomy
	 *
	 * @return \SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject
	 */
	public function addTaxonomy(ITaxonomy $ITaxonomy): ITaxonomyAwareDataTransferObject;


	/**
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy[]
	 */
	public function getTaxonomies(): array;
}
