<?php

namespace SRAG\Plugins\Hub2\Origin\Properties;

/**
 * Interface IOrgUnitMembershipOriginProperties
 *
 * @package SRAG\Plugins\Hub2\Origin\Properties
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitOriginProperties extends IOriginProperties {

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
