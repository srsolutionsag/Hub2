<?php namespace SRAG\Hub2\Object\GroupMembership;

use SRAG\Hub2\Object\ARObject;

/**
 * Class ARGroup
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupMembership extends ARObject implements IGroupMembership {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_group_mem';
	}
}
