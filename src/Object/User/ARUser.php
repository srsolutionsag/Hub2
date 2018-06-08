<?php

namespace SRAG\Plugins\Hub2\Object\User;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class ARUser
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARUser extends ARObject implements IUser {

	use ARMetadataAwareObject;
	const TABLE_NAME = 'sr_hub2_user';
}
