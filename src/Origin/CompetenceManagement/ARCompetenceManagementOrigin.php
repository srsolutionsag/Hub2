<?php

namespace srag\Plugins\Hub2\Origin\CompetenceManagement;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CompetenceManagement\CompetenceManagementOriginConfig;
use srag\Plugins\Hub2\Origin\Config\CompetenceManagement\ICompetenceManagementOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\CompetenceManagementProperties;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\ICompetenceManagementProperties;

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
    protected function getOriginConfig(array $data): ICompetenceManagementOriginConfig
    {
        return new CompetenceManagementOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data): ICompetenceManagementProperties
    {
        return new CompetenceManagementProperties($data);
    }

    /**
     * @inheritdoc
     */
    public function config(): ICompetenceManagementOriginConfig
    {
        return parent::config();
    }

    /**
     * @inheritdoc
     */
    public function properties(): ICompetenceManagementProperties
    {
        return parent::properties();
    }
}
