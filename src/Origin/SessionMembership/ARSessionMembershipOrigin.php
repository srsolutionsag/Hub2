<?php

namespace srag\Plugins\Hub2\Origin\SessionMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\SessionMembership\SessionMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\SessionMembership\SessionMembershipProperties;

/**
 * Class ARSessionMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionMembershipOrigin extends AROrigin implements ISessionMembershipOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(
        array $data
    ) : \srag\Plugins\Hub2\Origin\Config\SessionMembership\SessionMembershipOriginConfig {
        return new SessionMembershipOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(
        array $data
    ) : \srag\Plugins\Hub2\Origin\Properties\SessionMembership\SessionMembershipProperties {
        return new SessionMembershipProperties($data);
    }
}
