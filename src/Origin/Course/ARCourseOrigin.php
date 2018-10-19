<?php

namespace srag\Plugins\Hub2\Origin\Course;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CourseOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CourseOriginProperties;

/**
 * Class ARCourseOrigin
 *
 * @package srag\Plugins\Hub2\Origin\Course
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
