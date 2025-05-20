<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\SessionMembership;

use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class ARSessionMembership
 * @package srag\Plugins\Hub2\Object\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionMembership extends ARObject implements ISessionMembership
{
    public const TABLE_NAME = 'sr_hub2_session_mem';
}
