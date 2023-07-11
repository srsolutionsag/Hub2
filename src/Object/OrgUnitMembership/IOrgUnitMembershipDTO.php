<?php

namespace srag\Plugins\Hub2\Object\OrgUnitMembership;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IOrgUnitMembershipDTO
 * @package srag\Plugins\Hub2\Object\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitMembershipDTO extends IDataTransferObject
{
    /**
     * @var int
     */
    public const ORG_UNIT_ID_TYPE_OBJ_ID = 1;
    /**
     * @var int
     */
    public const ORG_UNIT_ID_TYPE_EXTERNAL_EXT_ID = 2;
    /**
     * @var int
     */
    public const POSITION_EMPLOYEE = 1;
    /**
     * @var int
     */
    public const POSITION_SUPERIOR = 2;

    public function getOrgUnitId() : string;

    public function setOrgUnitId(string $org_unit_id) : self;

    public function getOrgUnitIdType() : int;

    public function setOrgUnitIdType(int $org_unit_id_type) : self;

    public function getUserId() : int;

    public function setUserId(int $user_id) : self;

    public function getPosition() : int;

    public function setPosition(int $position) : self;
}
