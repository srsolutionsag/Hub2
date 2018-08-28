<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use ilHub2Plugin;
use ilObject;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class ITaxonomyImplementationFactory
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyImplementationFactory implements ITaxonomyImplementationFactory {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


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

		return NULL;
	}
}
