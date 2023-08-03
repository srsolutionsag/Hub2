<?php

namespace srag\Plugins\Hub2\Origin\GroupMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\GroupMembership\GroupMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\GroupMembership\GroupMembershipProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARGroupMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupMembershipOrigin extends AROrigin implements IGroupMembershipOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new GroupMembershipOriginConfig($data);
    }


    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new GroupMembershipProperties($data);
    }
}
