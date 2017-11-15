<?php namespace SRAG\Plugins\Hub2\Object\User;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;

/**
 * Class ARUser
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARUser extends ARMetadataAwareObject implements IUser {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_user';
	}
}