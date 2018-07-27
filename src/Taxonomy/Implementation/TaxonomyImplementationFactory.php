<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use ilObject;
use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class ITaxonomyImplementationFactory
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyImplementationFactory implements ITaxonomyImplementationFactory {

	/**
	 * @inheritdoc
	 */
	public function taxonomy(ITaxonomy $Taxonomy, ilObject $ilias_object): ITaxonomyImplementation {
		switch ($Taxonomy->getMode()) {
			case ITaxonomy::MODE_CREATE:
				return new TaxonomyCreate($Taxonomy, (int)$ilias_object->getRefId());
			case ITaxonomy::MODE_SELECT:
				return new TaxonomySelect($Taxonomy, (int)$ilias_object->getRefId());
		}
	}
}
