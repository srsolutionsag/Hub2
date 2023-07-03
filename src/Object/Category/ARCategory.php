<?php

namespace srag\Plugins\Hub2\Object\Category;

use srag\Plugins\Hub2\Object\ARDidacticTemplateAwareObject;
use srag\Plugins\Hub2\Object\ARMetadataAwareObject;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\ARTaxonomyAwareObject;

/**
 * Class ARCategory
 * @package srag\Plugins\Hub2\Object\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCategory extends ARObject implements ICategory
{
    use ARMetadataAwareObject;
    use ARTaxonomyAwareObject;
    use ARDidacticTemplateAwareObject;

    public const TABLE_NAME = 'sr_hub2_category';
}
