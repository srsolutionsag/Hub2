<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\Category;

use srag\Plugins\Hub2\Object\IDidacticTemplateAwareObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;

/**
 * Interface ICategory
 * @package srag\Plugins\Hub2\Object\Category
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICategory extends IObject, IMetadataAwareObject, ITaxonomyAwareObject, IDidacticTemplateAwareObject
{
}
