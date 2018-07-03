<?php

namespace SRAG\Plugins\Hub2\Object\GroupMembership;

use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class ARGroup
 *
 * @package SRAG\Plugins\Hub2\Object\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupMembership extends ARObject implements IGroupMembership {

	const TABLE_NAME = 'sr_hub2_group_mem';
}
