<?php

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\Object\IDidacticTemplateAwareObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;

/**
 * Interface IGroup
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroup extends IObject, IMetadataAwareObject, ITaxonomyAwareObject, IDidacticTemplateAwareObject
{
}
