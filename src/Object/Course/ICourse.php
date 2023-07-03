<?php

namespace srag\Plugins\Hub2\Object\Course;

use srag\Plugins\Hub2\Object\IDidacticTemplateAwareObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;

/**
 * Interface ICourse
 * @package srag\Plugins\Hub2\Object\Course
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourse extends IObject, IMetadataAwareObject, ITaxonomyAwareObject, IDidacticTemplateAwareObject
{
}
