<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin\Properties\CourseMembership;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface ICourseMembershipProperties
 * @package srag\Plugins\Hub2\Origin\Properties\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseMembershipProperties extends IOriginProperties
{
    public const DELETE_MODE = 'delete_mode';
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_DELETE = 1;
}
