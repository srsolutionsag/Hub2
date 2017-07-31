<?php namespace SRAG\Hub2\Object;

/**
 * Class ARCategory
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARCategory extends ARObject implements ICategory {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_category';
	}
}