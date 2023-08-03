<?php

namespace srag\Plugins\Hub2\Origin\CourseMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CourseMembership\CourseMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CourseMembership\CourseMembershipProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARCourseMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourseMembershipOrigin extends AROrigin implements ICourseMembershipOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new CourseMembershipOriginConfig($data);
    }

    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new CourseMembershipProperties($data);
    }
}
