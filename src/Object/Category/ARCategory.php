<?php

namespace SRAG\Plugins\Hub2\Object\Category;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\ARTaxonomyAwareObject;

/**
 * Class ARCategory
 *
 * @package SRAG\Plugins\Hub2\Object\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCategory extends ARObject implements ICategory {

	use ARMetadataAwareObject;
	use ARTaxonomyAwareObject;
	const TABLE_NAME = 'sr_hub2_category';
}
