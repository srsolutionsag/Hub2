<?php

namespace srag\Plugins\Hub2\Object\CourseMembership;

use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class ARCourse
 * @package srag\Plugins\Hub2\Object\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourseMembership extends ARObject implements ICourseMembership
{
    public const TABLE_NAME = 'sr_hub2_course_mem';
}
