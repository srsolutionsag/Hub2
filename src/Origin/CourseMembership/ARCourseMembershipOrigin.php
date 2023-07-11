<?php

namespace srag\Plugins\Hub2\Origin\CourseMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CourseMembership\CourseMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CourseMembership\CourseMembershipProperties;

/**
 * Class ARCourseMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourseMembershipOrigin extends AROrigin implements ICourseMembershipOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(
        array $data
    ) : \srag\Plugins\Hub2\Origin\Config\CourseMembership\CourseMembershipOriginConfig {
        return new CourseMembershipOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(
        array $data
    ) : \srag\Plugins\Hub2\Origin\Properties\CourseMembership\CourseMembershipProperties {
        return new CourseMembershipProperties($data);
    }
}
