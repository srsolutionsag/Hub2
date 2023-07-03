<?php

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
