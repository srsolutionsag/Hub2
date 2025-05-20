<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\User;

use srag\Plugins\Hub2\Object\ARMetadataAwareObject;
use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class ARUser
 * @package srag\Plugins\Hub2\Object\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARUser extends ARObject implements IUser
{
    use ARMetadataAwareObject;

    public const TABLE_NAME = 'sr_hub2_user';
}
