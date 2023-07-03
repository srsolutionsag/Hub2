<?php

namespace srag\Plugins\Hub2\Origin\Properties\OrgUnit;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitProperties
 * @package srag\Plugins\Hub2\Origin\Properties\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitProperties extends IOriginProperties
{
    /**
     * @var string
     */
    public const MOVE = "move";
    /**
     * @var string
     */
    public const DELETE_MODE = "delete_mode";
    /**
     * @var int
     */
    public const DELETE_MODE_NONE = 0;
    /**
     * @var int
     */
    public const DELETE_MODE_DELETE = 1;
    /**
     * @var string
     */
    public const PROP_DESCRIPTION = "description";
    /**
     * @var string
     */
    public const PROP_EXT_ID = "ext_id";
    /**
     * @var string
     */
    public const PROP_ORG_UNIT_TYPE = "org_unit_type";
    /**
     * @var string
     */
    public const PROP_OWNER = "owner";
    /**
     * @var string
     */
    public const PROP_PARENT_ID = "parent_id";
    /**
     * @var string
     */
    public const PROP_PARENT_ID_TYPE = "parent_id_type";
    /**
     * @var string
     */
    public const PROP_TITLE = "title";
}
