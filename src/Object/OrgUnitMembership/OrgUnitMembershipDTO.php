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
    /**
     * @var string
     */
    protected $org_unit_id = "";
    /**
     * @var int
     */
    protected $org_unit_id_type = self::ORG_UNIT_ID_TYPE_OBJ_ID;
    /**
     * @var int
     */
    protected $user_id;
    /**
     * @var int
     */
    protected $position;

    public function __construct(string $org_unit_id, int $user_id, int $position)
    {
        parent::__construct(implode(FakeOrgUnitMembershipObject::GLUE, [$org_unit_id, $user_id, $position]));
        $this->org_unit_id = $org_unit_id;
        $this->user_id = $user_id;
        $this->position = $position;
    }

    /**
     * @inheritdoc
     */
    public function getOrgUnitId() : string
    {
        return $this->org_unit_id;
    }

    /**
     * @inheritdoc
     */
    public function setOrgUnitId(string $org_unit_id) : IOrgUnitMembershipDTO
    {
        $this->org_unit_id = $org_unit_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrgUnitIdType() : int
    {
        return $this->org_unit_id_type;
    }

    /**
     * @inheritdoc
     */
    public function setOrgUnitIdType(int $org_unit_id_type) : IOrgUnitMembershipDTO
    {
        $this->org_unit_id_type = $org_unit_id_type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUserId() : int
    {
        return $this->user_id;
    }

    /**
     * @inheritdoc
     */
    public function setUserId(int $user_id) : IOrgUnitMembershipDTO
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPosition() : int
    {
        return $this->position;
    }

    /**
     * @inheritdoc
     */
    public function setPosition(int $position) : IOrgUnitMembershipDTO
    {
        $this->position = $position;

        return $this;
    }
}
