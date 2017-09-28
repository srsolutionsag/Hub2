<?php namespace SRAG\Hub2\Object\CourseMembership;

use SRAG\Hub2\Object\ARObject;

/**
 * Class ARCourse
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourseMembership extends ARObject implements ICourseMembership {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_course_mem';
	}
}
ARCourseMembership::installDB();