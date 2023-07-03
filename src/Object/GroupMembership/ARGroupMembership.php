<?php

namespace srag\Plugins\Hub2\Object\GroupMembership;

use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class ARGroup
 * @package srag\Plugins\Hub2\Object\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupMembership extends ARObject implements IGroupMembership
{
    public const TABLE_NAME = 'sr_hub2_group_mem';
}
