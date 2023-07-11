<?php

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
