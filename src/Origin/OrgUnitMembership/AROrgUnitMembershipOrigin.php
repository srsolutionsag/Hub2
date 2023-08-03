<?php

namespace srag\Plugins\Hub2\Origin\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\OrgUnitMembership\OrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership\OrgUnitMembershipProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class AROrgUnitMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class AROrgUnitMembershipOrigin extends AROrigin implements IOrgUnitMembershipOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new OrgUnitMembershipOriginConfig($data);
    }

    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new OrgUnitMembershipProperties($data);
    }
}
