<?php

namespace srag\Plugins\Hub2\Origin\Course;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Course\CourseOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;

/**
 * Class ARCourseOrigin
 * @package srag\Plugins\Hub2\Origin\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourseOrigin extends AROrigin implements ICourseOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data) : \srag\Plugins\Hub2\Origin\Config\Course\CourseOriginConfig
    {
        return new CourseOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data) : \srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties
    {
        return new CourseProperties($data);
    }
}
