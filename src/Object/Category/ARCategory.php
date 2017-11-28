<?php

namespace SRAG\Plugins\Hub2\Object\Category;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\ARTaxonomyAwareObject;

/**
 * Class ARCategory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Object\Category
 */
class ARCategory extends ARObject implements ICategory {

	use ARMetadataAwareObject;
	use ARTaxonomyAwareObject;


	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_category';
	}
}