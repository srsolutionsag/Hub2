<?php

namespace SRAG\Plugins\Hub2\Origin\Properties;

/**
 * Interface IOrgUnitMembershipOriginProperties
 *
 * @package SRAG\Plugins\Hub2\Origin\Properties
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitOriginProperties extends IOriginProperties {

	const PROP_DESCRIPTION = "description";
	const PROP_EXT_ID = "ext_id";
	const PROP_ORG_UNIT_TYPE = "org_unit_type";
	const PROP_OWNER = "owner";
	const PROP_PARENT_ID = "parent_id";
	const PROP_PARENT_ID_TYPE = "parent_id_type";
	const PROP_TITLE = "title";
}
