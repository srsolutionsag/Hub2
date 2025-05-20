<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\CourseMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class CourseMembershipDTO
 * @package srag\Plugins\Hub2\Object\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipDTO extends DataTransferObject implements ICourseMembershipDTO
{
    public function __construct($course_ext_id, $user_id)
    {
        parent::__construct(implode(FakeIliasMembershipObject::GLUE, [$course_ext_id, $user_id]));
        $this->courseId = $course_ext_id;
        $this->userId = $user_id;
    }

    /**
     * @var int
     */
    protected $courseIdType = self::COURSE_ID_TYPE_REF_ID;
    /**
     * @var int
     */
    protected $courseId;
    /**
     * @var int
     */
    protected $userId;
    /**
     * @var int
     */
    protected $role = self::ROLE_MEMBER;
    /**
     * @var bool
     */
    protected $isContact = false;
    /**
     * @var bool
     */
    protected $hasNotification = false;

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @param int $courseId
     */
    public function setCourseId($courseId): CourseMembershipDTO
    {
        $this->courseId = $courseId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId(int $userId): CourseMembershipDTO
    {
        $this->userId = $userId;

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
     */
    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCourseIdType(): int
    {
        return $this->courseIdType;
    }

    public function setCourseIdType(int $courseIdType): CourseMembershipDTO
    {
        $this->courseIdType = $courseIdType;

        return $this;
    }

    public function isContact(): bool
    {
        return $this->isContact;
    }

    /**
     * @return $this
     */
    public function setIsContact(bool $isContact): self
    {
        $this->isContact = $isContact;

        return $this;
    }

    /**
     * @return $this
     */
    public function setHasNotification(bool $notification): self
    {
        $this->hasNotification = $notification;

        return $this;
    }

    public function hasNotification(): bool
    {
        return $this->hasNotification;
    }
}
