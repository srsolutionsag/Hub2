<?php

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
