<?php namespace SRAG\Plugins\Hub2\Object\GroupMembership;

use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class GroupMembershipDTO
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipDTO extends DataTransferObject {

	/**
	 * @inheritDoc
	 */
	public function __construct($group_ext_id, $user_id) {
		parent::__construct("{$group_ext_id}|||{$user_id}");
	}


	const ROLE_MEMBER = 2;
	const ROLE_ADMIN = 1;
	/**
	 * @var int
	 */
	private $ilias_group_ref_id;
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
	public function getIliasGroupRefId() {
		return $this->ilias_group_ref_id;
	}


	/**
	 * @param int $ilias_group_ref_id
	 *
	 * @return GroupMembershipDTO
	 */
	public function setIliasGroupRefId(int $ilias_group_ref_id): GroupMembershipDTO {
		$this->ilias_group_ref_id = $ilias_group_ref_id;

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
	 * @return GroupMembershipDTO
	 */
	public function setUserId(int $user_id): GroupMembershipDTO {
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
	 * @return GroupMembershipDTO
	 */
	public function setRole($role) {
		$this->role = $role;

		return $this;
	}
}