<?php namespace SRAG\Hub2\Origin\Course;

use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Origin\Config\CourseOriginConfig;
use SRAG\Hub2\Origin\Properties\CourseOriginProperties;

/**
 * Class ARCourseOrigin
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
 */
class ARCourseOrigin extends AROrigin implements ICourseOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new CourseOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new CourseOriginProperties($data);
	}
}