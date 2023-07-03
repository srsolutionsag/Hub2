<?php

namespace srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitMembershipProperties
 * @package srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitMembershipProperties extends IOriginProperties
{
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
    public const PROP_ORG_UNIT_ID = "org_unit_id";
    /**
     * @var string
     */
    public const PROP_ORG_UNIT_ID_TYPE = "org_unit_id_type";
    /**
     * @var string
     */
    public const PROP_POSITION = "position";
    /**
     * @var string
     */
    public const PROP_USER_ID = "user_id";
}
