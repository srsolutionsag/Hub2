<?php

namespace srag\Plugins\Hub2\Origin\CompetenceManagement;

use srag\Plugins\Hub2\Origin\Config\CompetenceManagement\ICompetenceManagementOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\ICompetenceManagementProperties;

/**
 * Interface ICompetenceManagementOrigin
 * @package srag\Plugins\Hub2\Origin\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ICompetenceManagementOrigin extends IOrigin
{
    /**
     * @return ICompetenceManagementOriginConfig
     */
    public function config(): \srag\Plugins\Hub2\Origin\Config\IOriginConfig;

    /**
     * @return ICompetenceManagementProperties
     */
    public function properties(): \srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
}
