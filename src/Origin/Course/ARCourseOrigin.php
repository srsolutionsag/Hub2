<?php

namespace SRAG\Plugins\Hub2\Origin\Course;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\CourseOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\CourseOriginProperties;

/**
 * Class ARCourseOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
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
