<?php

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
    const ROLE_MEMBER = 2;
    const ROLE_TUTOR = 3;
    const ROLE_ADMIN = 1;
    const COURSE_ID_TYPE_REF_ID = 1;
    const COURSE_ID_TYPE_EXTERNAL_EXT_ID = 2;
    const GLUE = IObjectRepository::GLUE;
}
