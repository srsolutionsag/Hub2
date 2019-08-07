<?php

namespace srag\Plugins\Hub2\Object\OrgUnitMembership;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IOrgUnitMembershipDTO
 *
 * @package srag\Plugins\Hub2\Object\OrgUnitMembership
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitMembershipDTO extends IDataTransferObject {

	/**
	 * @var int
	 */
	const ORG_UNIT_ID_TYPE_OBJ_ID = 1;
	/**
	 * @var int
	 */
	const ORG_UNIT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	/**
	 * @var int
	 */
	const POSITION_EMPLOYEE = 1;
	/**
	 * @var int
	 */
	const POSITION_SUPERIOR = 2;


	/**
	 * @return string
	 */
	public function getOrgUnitId(): string;


	/**
	 * @param string $org_unit_id
	 *
	 * @return self
	 */
	public function setOrgUnitId(string $org_unit_id): self;


	/**
	 * @return int
	 */
	public function getOrgUnitIdType(): int;


	/**
	 * @param int $org_unit_id_type
	 *
	 * @return self
	 */
	public function setOrgUnitIdType(int $org_unit_id_type): self;


	/**
	 * @return int
	 */
	public function getUserId(): int;


	/**
	 * @param int $user_id
	 *
	 * @return self
	 */
	public function setUserId(int $user_id): self;


	/**
	 * @return int
	 */
	public function getPosition(): int;


	/**
	 * @param int $position
	 *
	 * @return self
	 */
	public function setPosition(int $position): self;
}
