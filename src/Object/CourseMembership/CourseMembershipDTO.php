<?php namespace SRAG\Plugins\Hub2\Object\CourseMembership;

use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class CourseMembershipDTO
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipDTO extends DataTransferObject {

	/**
	 * @inheritDoc
	 */
	public function __construct($course_ext_id, $user_id) {
		parent::__construct("{$course_ext_id}|||{$user_id}");
		$this->courseId = $course_ext_id;
		$this->userId = $user_id;
	}


	const ROLE_MEMBER = 2;
	const ROLE_TUTOR = 3;
	const ROLE_ADMIN = 1;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	/**
	 * @var int
	 */
	private $courseIdType = self::PARENT_ID_TYPE_REF_ID;
	/**
	 * @var int
	 */
	private $courseId;
	/**
	 * @var int
	 */
	private $userId;
	/**
	 * @var int
	 */
	protected $role = self::ROLE_MEMBER;


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
	public function setCourseId(int $courseId): CourseMembershipDTO {
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
}