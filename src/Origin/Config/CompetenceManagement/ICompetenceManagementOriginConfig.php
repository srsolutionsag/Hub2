<?php

namespace srag\Plugins\Hub2\Origin\Config\CompetenceManagement;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Interface ICompetenceManagementOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ICompetenceManagementOriginConfig extends IOriginConfig
{
    /**
     * @var string
     */
    public const ID_IF_NO_PARENT_ID = "id_if_no_parent_id";

    public function getIdIfNoParentId() : int;
}
