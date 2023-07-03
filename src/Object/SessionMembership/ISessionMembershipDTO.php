<?php

namespace srag\Plugins\Hub2\Object\SessionMembership;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface ISessionMembershipDTO
 * @package srag\Plugins\Hub2\Object\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISessionMembershipDTO extends IDataTransferObject
{
    public const ROLE_MEMBER = 1;
    public const PARENT_ID_TYPE_REF_ID = 1;
    public const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
}
