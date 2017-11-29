<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use SRAG\Plugins\Hub2\Taxonomy\ITaxonomy;
use SRAG\Plugins\Hub2\Object\Category\CategoryDTO;
use SRAG\Plugins\Hub2\Object\Course\CourseDTO;
use SRAG\Plugins\Hub2\Object\Group\GroupDTO;
use SRAG\Plugins\Hub2\Object\Session\SessionDTO;
use SRAG\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ITaxonomyImplementationFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyImplementationFactory implements ITaxonomyImplementationFactory {

	/**
	 * @inheritdoc
	 */
	public function taxonomy(ITaxonomy $Taxonomy, \ilObject $ilias_object): ITaxonomyImplementation {
		switch ($Taxonomy->getMode()) {
			case ITaxonomy::MODE_CREATE:
				return new TaxonomyCreate($Taxonomy, (int)$ilias_object->getRefId());
			case ITaxonomy::MODE_SELECT:
				return new TaxonomySelect($Taxonomy, (int)$ilias_object->getRefId());
		}
	}
}

