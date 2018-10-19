<?php

namespace srag\Plugins\Hub2\Object\SessionMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class SessionMembershipDTO
 *
 * @package srag\Plugins\Hub2\Object\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipDTO extends DataTransferObject {

	const ROLE_MEMBER = 1;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
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
	 * @inheritDoc
	 */
	public function __construct($session_id, $user_id) {
		parent::__construct(implode(FakeIliasMembershipObject::GLUE, [ $session_id, $user_id ]));
		$this->sessionId = $session_id;
		$this->userId = $user_id;
	}


	/**
	 * @return string
	 */
	public function getSessionId(): string {
		return $this->sessionId;
	}


	/**
	 * @param string $sessionId
	 *
	 * @return SessionMembershipDTO
	 */
	public function setSessionId(string $sessionId): SessionMembershipDTO {
		$this->sessionId = $sessionId;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getSessionIdType(): int {
		return $this->sessionIdType;
	}


	/**
	 * @param int $sessionIdType
	 *
	 * @return SessionMembershipDTO
	 */
	public function setSessionIdType(int $sessionIdType): SessionMembershipDTO {
		$this->sessionIdType = $sessionIdType;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRole(): int {
		return $this->role;
	}


	/**
	 * @param int $role
	 *
	 * @return SessionMembershipDTO
	 */
	public function setRole(int $role): SessionMembershipDTO {
		$this->role = $role;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->userId;
	}


	/**
	 * @param int $userId
	 *
	 * @return SessionMembershipDTO
	 */
	public function setUserId(int $userId): SessionMembershipDTO {
		$this->userId = $userId;

		return $this;
	}
}
