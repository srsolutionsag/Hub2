<?php namespace SRAG\Plugins\Hub2\Sync\Processor\Course;

/**
 * Interface ICourseActivities
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Sync\Processor
 */
interface ICourseActivities {

	/**
	 * Returns true if any activities happened in the given course, false otherwise.
	 *
	 * @param \ilObjCourse $ilObjCourse
	 *
	 * @return bool
	 */
	public function hasActivities(\ilObjCourse $ilObjCourse);
}