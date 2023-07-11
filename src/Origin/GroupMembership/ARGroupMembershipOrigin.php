<?php

namespace srag\Plugins\Hub2\Origin\GroupMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\GroupMembership\GroupMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\GroupMembership\GroupMembershipProperties;

/**
 * Class ARGroupMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupMembershipOrigin extends AROrigin implements IGroupMembershipOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(
        array $data
    ) : \srag\Plugins\Hub2\Origin\Config\GroupMembership\GroupMembershipOriginConfig {
        return new GroupMembershipOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(
        array $data
    ) : \srag\Plugins\Hub2\Origin\Properties\GroupMembership\GroupMembershipProperties {
        return new GroupMembershipProperties($data);
    }
}
