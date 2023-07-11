<?php

namespace srag\Plugins\Hub2\UI\GroupMembership;

use srag\Plugins\Hub2\Origin\GroupMembership\ARGroupMembershipOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class GroupMembershipOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARGroupMembershipOrigin
     */
    protected $origin;
}
