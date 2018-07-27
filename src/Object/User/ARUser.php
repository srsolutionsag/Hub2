<?php

namespace SRAG\Plugins\Hub2\Object\User;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class ARUser
 *
 * @package SRAG\Plugins\Hub2\Object\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARUser extends ARObject implements IUser {

	use ARMetadataAwareObject;
	const TABLE_NAME = 'sr_hub2_user';
}
