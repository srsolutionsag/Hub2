<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\CourseMembership;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\IObjectRepository;

/**
 * Interface ICourseMembershipDTO
 * @package srag\Plugins\Hub2\Object\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseMembershipDTO extends IDataTransferObject
{
    public const ROLE_MEMBER = 2;
    public const ROLE_TUTOR = 3;
    public const ROLE_ADMIN = 1;
    public const COURSE_ID_TYPE_REF_ID = 1;
    public const COURSE_ID_TYPE_EXTERNAL_EXT_ID = 2;
    public const GLUE = IObjectRepository::GLUE;
}
