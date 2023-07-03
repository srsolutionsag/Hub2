<?php

namespace srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership;

use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;

/**
 * Interface IOrgUnitMembershipSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitMembershipSyncProcessor extends IObjectSyncProcessor
{
    /**
     * @var int
     */
    public const IL_POSITION_EMPLOYEE = 1;
    /**
     * @var int
     */
    public const IL_POSITION_SUPERIOR = 2;
}
