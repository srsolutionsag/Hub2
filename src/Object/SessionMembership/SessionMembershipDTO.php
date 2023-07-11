<?php

namespace srag\Plugins\Hub2\Object\SessionMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class SessionMembershipDTO
 * @package srag\Plugins\Hub2\Object\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipDTO extends DataTransferObject implements ISessionMembershipDTO
{
    /**
     * @var string
     */
    protected $sessionId;
    /**
     * @var int
     */
    protected $sessionIdType = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var int
     */
    protected $role;
    /**
     * @var int
     */
    protected $userId;
    /**
     * @var bool
     */
    protected $isContact = false;

    /**
     * @inheritdoc
     */
    public function __construct($session_id, $user_id)
    {
        parent::__construct(implode(FakeIliasMembershipObject::GLUE, [$session_id, $user_id]));
        $this->sessionId = $session_id;
        $this->userId = $user_id;
    }

    public function getSessionId() : string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId) : SessionMembershipDTO
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getSessionIdType() : int
    {
        return $this->sessionIdType;
    }

    public function setSessionIdType(int $sessionIdType) : SessionMembershipDTO
    {
        $this->sessionIdType = $sessionIdType;

        return $this;
    }

    public function getRole() : int
    {
        return $this->role;
    }

    public function setRole(int $role) : SessionMembershipDTO
    {
        $this->role = $role;

        return $this;
    }

    public function getUserId() : int
    {
        return $this->userId;
    }

    public function setUserId(int $userId) : SessionMembershipDTO
    {
        $this->userId = $userId;

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
