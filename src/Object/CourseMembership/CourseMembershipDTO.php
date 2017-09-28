<?php namespace SRAG\Hub2\Object\CourseMembership;

use SRAG\Hub2\Object\DataTransferObject;

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
	}


	const ROLE_MEMBER = 2;
	const ROLE_TUTOR = 3;
	const ROLE_ADMIN = 1;
	/**
	 * @var int
	 */
	private $ilias_course_ref_id;
	/**
	 * @var int
	 */
	private $user_id;
	/**
	 * @var
	 */
	protected $role = self::ROLE_MEMBER;


	/**
	 * @return int
	 */
	public function getIliasCourseRefId() {
		return $this->ilias_course_ref_id;
	}


	/**
	 * @param int $ilias_course_ref_id
	 *
	 * @return CourseMembershipDTO
	 */
	public function setIliasCourseRefId(int $ilias_course_ref_id): CourseMembershipDTO {
		$this->ilias_course_ref_id = $ilias_course_ref_id;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->user_id;
	}


	/**
	 * @param int $user_id
	 *
	 * @return CourseMembershipDTO
	 */
	public function setUserId(int $user_id): CourseMembershipDTO {
		$this->user_id = $user_id;

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
}