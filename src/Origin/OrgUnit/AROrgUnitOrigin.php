<?php

namespace srag\Plugins\Hub2\Origin\OrgUnit;

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
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data) : \srag\Plugins\Hub2\Origin\Config\IOriginConfig
    {
        return new OrgUnitOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data) : \srag\Plugins\Hub2\Origin\Properties\IOriginProperties
    {
        return new OrgUnitProperties($data);
    }
}
