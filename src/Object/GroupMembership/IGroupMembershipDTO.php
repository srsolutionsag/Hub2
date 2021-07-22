<?php

namespace srag\Plugins\Hub2\Object\GroupMembership;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IGroupMembershipDTO
 * @package srag\Plugins\Hub2\Object\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupMembershipDTO extends IDataTransferObject
{

    const PARENT_ID_TYPE_REF_ID = 1;
    const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
    const ROLE_MEMBER = 2;
    const ROLE_ADMIN = 1;
}
