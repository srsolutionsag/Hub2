<?php

namespace srag\Plugins\Hub2\Object\GroupMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class GroupMembershipDTO
 * @package srag\Plugins\Hub2\Object\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipDTO extends DataTransferObject implements IGroupMembershipDTO
{
    /**
     * @var int
     */
    protected $ilias_group_ref_id;
    /**
     * @var int
     */
    protected $user_id;
    /**
     * @var
     */
    protected $role = self::ROLE_MEMBER;
    /**
     * @var string
     */
    protected $groupId;
    /**
     * @var int
     */
    protected $groupIdType;
    /**
     * @var bool
     */
    protected $isContact = false;

    /**
     * @inheritdoc
     */
    public function __construct($group_id, $user_id)
    {
        parent::__construct(implode(FakeIliasMembershipObject::GLUE, [$group_id, $user_id]));
        $this->setGroupId($group_id);
        $this->setUserId($user_id);
    }

    public function getGroupId() : string
    {
        return $this->groupId;
    }

    public function setGroupId(string $groupId) : GroupMembershipDTO
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getGroupIdType() : int
    {
        return $this->groupIdType;
    }

    public function setGroupIdType(int $groupIdType) : GroupMembershipDTO
    {
        $this->groupIdType = $groupIdType;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id) : GroupMembershipDTO
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     * @return GroupMembershipDTO
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function isContact() : bool
    {
        return $this->isContact;
    }

    /**
     * @return $this
     */
    public function setIsContact(bool $isContact)
    {
        $this->isContact = $isContact;

        return $this;
    }
}
