<?php

namespace srag\Plugins\Hub2\Origin\Properties\OrgUnit;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties\OrgUnit
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitProperties extends IOriginProperties {

	/**
	 * @var string
	 */
	const PROP_DESCRIPTION = "description";
	/**
	 * @var string
	 */
	const PROP_EXT_ID = "ext_id";
	/**
	 * @var string
	 */
	const PROP_ORG_UNIT_TYPE = "org_unit_type";
	/**
	 * @var string
	 */
	const PROP_OWNER = "owner";
	/**
	 * @var string
	 */
	const PROP_PARENT_ID = "parent_id";
	/**
	 * @var string
	 */
	const PROP_PARENT_ID_TYPE = "parent_id_type";
	/**
	 * @var string
	 */
	const PROP_TITLE = "title";
}
