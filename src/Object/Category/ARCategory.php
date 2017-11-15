<?php namespace SRAG\Plugins\Hub2\Object\Category;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;

/**
 * Class ARCategory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Object\Category
 */
class ARCategory extends ARMetadataAwareObject implements ICategory {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_category';
	}
}