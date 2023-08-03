<?php

namespace srag\Plugins\Hub2\Origin\CompetenceManagement;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CompetenceManagement\CompetenceManagementOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\CompetenceManagementProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARCompetenceManagementOrigin
 * @package srag\Plugins\Hub2\Origin\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ARCompetenceManagementOrigin extends AROrigin implements ICompetenceManagementOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new CompetenceManagementOriginConfig($data);
    }

    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new CompetenceManagementProperties($data);
    }
}
