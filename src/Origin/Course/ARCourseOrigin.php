<?php

namespace srag\Plugins\Hub2\Origin\Course;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Course\CourseOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARCourseOrigin
 * @package srag\Plugins\Hub2\Origin\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourseOrigin extends AROrigin implements ICourseOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new CourseOriginConfig($data);
    }


    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new CourseProperties($data);
    }
}
