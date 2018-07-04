<?php

namespace SRAG\Plugins\Hub2\Origin\OrgUnitMembership;

use SRAG\Plugins\Hub2\Origin\Config\IOrgUnitMembershipOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\Properties\IOrgUnitMembershipOriginProperties;

/**
 * Interface IOrgUnitMembershipOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\OrgUnitMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitMembershipOrigin extends IOrigin {

	/**
	 * @return IOrgUnitMembershipOriginConfig
	 */
	public function config(): IOrgUnitMembershipOriginConfig;


	/**
	 * @return IOrgUnitMembershipOriginProperties
	 */
	public function properties(): IOrgUnitMembershipOriginProperties;
}
