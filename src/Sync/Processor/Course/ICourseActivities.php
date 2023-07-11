<?php

namespace srag\Plugins\Hub2\Sync\Processor\Course;

use ilObjCourse;

/**
 * Interface ICourseActivities
 * @package srag\Plugins\Hub2\Sync\Processor\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseActivities
{
    /**
     * Returns true if any activities happened in the given course, false otherwise.
     * @return bool
     */
    public function hasActivities(ilObjCourse $ilObjCourse);
}
