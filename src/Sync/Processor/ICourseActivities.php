<?php namespace SRAG\Hub2\Sync\Processor;

/**
 * Interface ICourseActivities
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync\Processor
 */
interface ICourseActivities {

	/**
	 * Returns true if any activities happened in the given course, false otherwise.
	 *
	 * @param \ilObjCourse $ilObjCourse
	 * @return bool
	 */
	public function hasActivities(\ilObjCourse $ilObjCourse);

}