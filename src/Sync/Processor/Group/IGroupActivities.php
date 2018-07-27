<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\Group;

use ilObjGroup;

/**
 * Interface IGroupActivities
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupActivities {

	/**
	 * Returns true if any activities happened in the given group, false otherwise.
	 *
	 * @param ilObjGroup $ilObjGroup
	 *
	 * @return bool
	 */
	public function hasActivities(ilObjGroup $ilObjGroup);
}
