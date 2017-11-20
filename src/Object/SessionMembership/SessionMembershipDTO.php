<?php

namespace SRAG\Plugins\Hub2\Object\SessionMembership;

use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class SessionMembershipDTO
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipDTO extends DataTransferObject {

	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	/**
	 * @var string
	 */
	protected $parentId;
	/**
	 * @var int
	 */
	protected $parentIdType = self::PARENT_ID_TYPE_REF_ID;
	/**
	 * @var int
	 */
	protected $role;
	/**
	 * @var int
	 */
	protected $user_id;


	/**
	 * @return string
	 */
	public function getParentId(): string {
		return $this->parentId;
	}


	/**
	 * @param string $parentId
	 */
	public function setParentId(string $parentId) {
		$this->parentId = $parentId;
	}


	/**
	 * @return int
	 */
	public function getParentIdType(): int {
		return $this->parentIdType;
	}


	/**
	 * @param int $parentIdType
	 */
	public function setParentIdType(int $parentIdType) {
		$this->parentIdType = $parentIdType;
	}


	/**
	 * @return int
	 */
	public function getRole(): int {
		return $this->role;
	}


	/**
	 * @param int $role
	 */
	public function setRole(int $role) {
		$this->role = $role;
	}


	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->user_id;
	}


	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id) {
		$this->user_id = $user_id;
	}
}