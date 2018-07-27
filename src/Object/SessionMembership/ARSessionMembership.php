<?php

namespace SRAG\Plugins\Hub2\Object\SessionMembership;

use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class ARSessionMembership
 *
 * @package SRAG\Plugins\Hub2\Object\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionMembership extends ARObject implements ISessionMembership {

	const TABLE_NAME = 'sr_hub2_session_mem';
}
