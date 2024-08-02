<?php

namespace srag\Plugins\Hub2\Origin\OrgUnit;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\OrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\OrgUnitProperties;

/**
 * Class AROrgUnitOrigin
 * @package srag\Plugins\Hub2\Origin\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class AROrgUnitOrigin extends AROrigin implements IOrgUnitOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new OrgUnitOriginConfig($data);
    }


    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new OrgUnitProperties($data);
    }
}
