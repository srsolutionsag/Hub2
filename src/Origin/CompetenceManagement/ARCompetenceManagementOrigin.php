<?php

namespace srag\Plugins\Hub2\Origin\CompetenceManagement;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CompetenceManagement\CompetenceManagementOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\CompetenceManagementProperties;

/**
 * Class ARCompetenceManagementOrigin
 * @package srag\Plugins\Hub2\Origin\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ARCompetenceManagementOrigin extends AROrigin implements ICompetenceManagementOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data) : \srag\Plugins\Hub2\Origin\Config\IOriginConfig
    {
        return new CompetenceManagementOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data) : \srag\Plugins\Hub2\Origin\Properties\IOriginProperties
    {
        return new CompetenceManagementProperties($data);
    }
}
