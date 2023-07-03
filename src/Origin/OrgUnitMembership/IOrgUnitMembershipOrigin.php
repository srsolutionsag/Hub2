<?php

namespace srag\Plugins\Hub2\Origin\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\Config\OrgUnitMembership\IOrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership\IOrgUnitMembershipProperties;

/**
 * Interface IOrgUnitMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitMembershipOrigin extends IOrigin
{
    /**
     * @return IOrgUnitMembershipOriginConfig
     */
    public function config(): \srag\Plugins\Hub2\Origin\Config\IOriginConfig;

    /**
     * @return IOrgUnitMembershipProperties
     */
    public function properties(): \srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
}
