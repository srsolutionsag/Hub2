<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin\OrgUnit;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\IOrgUnitProperties;

/**
 * Interface IOrgUnitOrigin
 * @package srag\Plugins\Hub2\Origin\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitOrigin extends IOrigin
{
    /**
     * @return IOrgUnitOriginConfig
     */
    public function config(): IOriginConfig;

    /**
     * @return IOrgUnitProperties
     */
    public function properties(): IOriginProperties;
}
