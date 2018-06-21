<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\OrgUnitMembership;

use SRAG\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;

/**
 * Interface IOrgUnitMembershipSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\OrgUnitMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitMembershipSyncProcessor extends IObjectSyncProcessor {

	const IL_POSITION_EMPLOYEE = 1;
	const IL_POSITION_SUPERIOR = 2;
}
