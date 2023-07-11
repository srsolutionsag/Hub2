<?php

namespace srag\Plugins\Hub2\UI\SessionMembership;

use srag\Plugins\Hub2\Origin\SessionMembership\ARSessionMembershipOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class SessionMembershipOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARSessionMembershipOrigin
     */
    protected $origin;
}
