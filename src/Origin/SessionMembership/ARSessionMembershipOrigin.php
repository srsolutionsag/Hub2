<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin\SessionMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\SessionMembership\SessionMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\SessionMembership\SessionMembershipProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARSessionMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionMembershipOrigin extends AROrigin implements ISessionMembershipOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new SessionMembershipOriginConfig($data);
    }

    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new SessionMembershipProperties($data);
    }
}
