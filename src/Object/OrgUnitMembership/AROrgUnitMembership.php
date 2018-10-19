<?php

namespace srag\Plugins\Hub2\Object\OrgUnitMembership;

use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class AROrgUnitMembership
 *
 * @package srag\Plugins\Hub2\Object\OrgUnitMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class AROrgUnitMembership extends ARObject implements IOrgUnitMembership {

	const TABLE_NAME = "sr_hub2_org_unit_mem";
}
