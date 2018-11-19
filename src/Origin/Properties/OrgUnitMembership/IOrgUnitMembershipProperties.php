<?php

namespace srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitMembershipProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitMembershipProperties extends IOriginProperties {

	/**
	 * @var string
	 */
	const PROP_ORG_UNIT_ID = "org_unit_id";
	/**
	 * @var string
	 */
	const PROP_ORG_UNIT_ID_TYPE = "org_unit_id_type";
	/**
	 * @var string
	 */
	const PROP_POSITION = "position";
	/**
	 * @var string
	 */
	const PROP_USER_ID = "user_id";
}
