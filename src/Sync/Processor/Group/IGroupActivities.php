<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor\Group;

use ilObjGroup;

/**
 * Interface IGroupActivities
 * @package srag\Plugins\Hub2\Sync\Processor\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupActivities
{
    /**
     * Returns true if any activities happened in the given group, false otherwise.
     * @return bool
     */
    public function hasActivities(ilObjGroup $ilObjGroup);
}
