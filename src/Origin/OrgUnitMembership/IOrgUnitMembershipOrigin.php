<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
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
    public function config(): IOriginConfig;

    /**
     * @return IOrgUnitMembershipProperties
     */
    public function properties(): IOriginProperties;
}
