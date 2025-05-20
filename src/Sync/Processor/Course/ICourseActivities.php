<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
