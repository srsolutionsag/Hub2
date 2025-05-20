<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Object\IObject;

/**
 * Interface IObjectStatus
 * @package srag\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @deprecated
 */
interface IObjectStatusTransition
{
    /**
     * Transition from the current final status of the object to the next intermediate status.
     * If the current status is not a final one (e.g. CREATED, UPDATED, OUTDATED, IGNORED...), the
     * same final status is returned.
     * Note that this method returns the new status but does NOT set it on the passed object.
     * @deprecated
     */
    public function finalToIntermediate(IObject $object): int;
}
