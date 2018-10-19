<?php

namespace srag\Plugins\Hub2\Origin\Properties;

/**
 * Interface IOrgUnitOriginProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitMembershipOriginProperties extends IOriginProperties {

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
