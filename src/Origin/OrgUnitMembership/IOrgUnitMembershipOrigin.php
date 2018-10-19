<?php

namespace srag\Plugins\Hub2\Origin\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\Config\IOrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\Properties\IOrgUnitMembershipOriginProperties;

/**
 * Interface IOrgUnitMembershipOrigin
 *
 * @package srag\Plugins\Hub2\Origin\OrgUnitMembership
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
