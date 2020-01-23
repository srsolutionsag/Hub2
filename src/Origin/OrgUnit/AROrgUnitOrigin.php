<?php

namespace srag\Plugins\Hub2\Origin\OrgUnit;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\OrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\IOrgUnitProperties;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\OrgUnitProperties;

/**
 * Class AROrgUnitOrigin
 *
 * @package srag\Plugins\Hub2\Origin\OrgUnit
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class AROrgUnitOrigin extends AROrigin implements IOrgUnitOrigin
{

    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data) : IOrgUnitOriginConfig
    {
        return new OrgUnitOriginConfig($data);
    }


    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data) : IOrgUnitProperties
    {
        return new OrgUnitProperties($data);
    }


    /**
     * @inheritdoc
     */
    public function config() : IOrgUnitOriginConfig
    {
        return parent::config();
    }


    /**
     * @inheritdoc
     */
    public function properties() : IOrgUnitProperties
    {
        return parent::properties();
    }
}
