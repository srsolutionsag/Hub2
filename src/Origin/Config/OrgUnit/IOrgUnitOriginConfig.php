<?php

namespace srag\Plugins\Hub2\Origin\Config\OrgUnit;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Interface IOrgUnitOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitOriginConfig extends IOriginConfig
{
    /**
     * @var string
     */
    public const REF_ID_IF_NO_PARENT_ID = "ref_id_if_no_parent_id";

    public function getRefIdIfNoParentId() : int;
}
