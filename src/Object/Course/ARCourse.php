<?php namespace SRAG\Plugins\Hub2\Object\Course;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;

/**
 * Class ARCourse
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARCourse extends ARMetadataAwareObject implements ICourse {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_course';
	}


}