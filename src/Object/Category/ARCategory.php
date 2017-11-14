<?php namespace SRAG\Plugins\Hub2\Object\Category;

use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class ARCategory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Object\Category
 */
class ARCategory extends ARObject implements ICategory {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_category';
	}
}