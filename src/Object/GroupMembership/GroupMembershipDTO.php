<?php namespace SRAG\Plugins\Hub2\Object\GroupMembership;

use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class GroupMembershipDTO
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipDTO extends DataTransferObject {

	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;

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
	 * @inheritDoc
	 */
	public function __construct($group_id, $user_id) {
		parent::__construct("{$group_id}|||{$user_id}");
		$this->groupId = $group_id;
		$this->userId = $user_id;
	}

	/**
	 * @return string
	 */
	public function getGroupId(): string {
		return $this->groupId;
	}


	/**
	 * @param string $groupId
	 * @return GroupMembershipDTO
	 */
	public function setGroupId(string $groupId): GroupMembershipDTO {
		$this->groupId = $groupId;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getGroupIdType(): int {
		return $this->groupIdType;
	}


	/**
	 * @param int $groupIdType
	 *
	 * @return GroupMembershipDTO
	 */
	public function setGroupIdType(int $groupIdType): GroupMembershipDTO {
		$this->groupIdType = $groupIdType;

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