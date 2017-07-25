<?php namespace SRAG\Hub2\Object;

/**
 * Class ARCourse
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARCourse extends ARObject {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_course';
	}
}