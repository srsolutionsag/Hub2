<?php

namespace srag\Plugins\Hub2\Object\OrgUnitMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership\FakeOrgUnitMembershipObject;

/**
 * Class OrgUnitMembershipDTO
 * @package srag\Plugins\Hub2\Object\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitMembershipDTO extends DataTransferObject implements IOrgUnitMembershipDTO
{
    protected string $org_unit_id;
    /**
     * @var int
     */
    protected $org_unit_id_type = self::ORG_UNIT_ID_TYPE_OBJ_ID;
    protected int $user_id;
    protected int $position;

    public function __construct(string $org_unit_id, int $user_id, int $position)
    {
        parent::__construct(implode(FakeOrgUnitMembershipObject::GLUE, [$org_unit_id, $user_id, $position]));
        $this->org_unit_id = $org_unit_id;
        $this->user_id = $user_id;
        $this->position = $position;
    }


    public function getOrgUnitId(): string
    {
        return $this->org_unit_id;
    }


    public function setOrgUnitId(string $org_unit_id): IOrgUnitMembershipDTO
    {
        $this->org_unit_id = $org_unit_id;

        return $this;
    }


    public function getOrgUnitIdType(): int
    {
        return $this->org_unit_id_type;
    }


    public function setOrgUnitIdType(int $org_unit_id_type): IOrgUnitMembershipDTO
    {
        $this->org_unit_id_type = $org_unit_id_type;

        return $this;
    }


    public function getUserId(): int
    {
        return $this->user_id;
    }


    public function setUserId(int $user_id): IOrgUnitMembershipDTO
    {
        $this->user_id = $user_id;

        return $this;
    }


    public function getPosition(): int
    {
        return $this->position;
    }


    public function setPosition(int $position): IOrgUnitMembershipDTO
    {
        $this->position = $position;

        return $this;
    }
}
