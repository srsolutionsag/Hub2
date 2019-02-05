<?php

namespace srag\Plugins\Hub2\Object\CourseMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class CourseMembershipDTO
 *
 * @package srag\Plugins\Hub2\Object\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipDTO extends DataTransferObject implements ICourseMembershipDTO {

	/**
	 * @inheritdoc
	 */
	public function __construct($course_ext_id, $user_id) {
		parent::__construct(implode(FakeIliasMembershipObject::GLUE, [ $course_ext_id, $user_id ]));
		$this->courseId = $course_ext_id;
		$this->userId = $user_id;
	}


	const ROLE_MEMBER = 2;
	const ROLE_TUTOR = 3;
	const ROLE_ADMIN = 1;
	const COURSE_ID_TYPE_REF_ID = 1;
	const COURSE_ID_TYPE_EXTERNAL_EXT_ID = 2;
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
	 * @return int
	 */
	public function getCourseId() {
		return $this->courseId;
	}


	/**
	 * @param int $courseId
	 *
	 * @return CourseMembershipDTO
	 */
	public function setCourseId($courseId): CourseMembershipDTO {
		$this->courseId = $courseId;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->userId;
	}


	/**
	 * @param int $userId
	 *
	 * @return CourseMembershipDTO
	 */
	public function setUserId(int $userId): CourseMembershipDTO {
		$this->userId = $userId;

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getRole() {
		return $this->role;
	}


	/**
	 * @param mixed $role
	 *
	 * @return CourseMembershipDTO
	 */
	public function setRole($role) {
		$this->role = $role;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getCourseIdType(): int {
		return $this->courseIdType;
	}


	/**
	 * @param int $courseIdType
	 *
	 * @return CourseMembershipDTO
	 */
	public function setCourseIdType(int $courseIdType): CourseMembershipDTO {
		$this->courseIdType = $courseIdType;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function isContact(): bool {
		return $this->isContact;
	}


	/**
	 * @param bool $isContact
	 *
	 * @return $this
	 */
	public function setIsContact(bool $isContact) {
		$this->isContact = $isContact;

		return $this;
	}
}
