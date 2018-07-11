<?php

namespace SRAG\Plugins\Hub2\Object\OrgUnitMembership;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IOrgUnitMembershipDTO
 *
 * @package SRAG\Plugins\Hub2\Object\OrgUnitMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
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
	 * @return int|string
	 */
	public function getOrgUnitId();


	/**
	 * @param int|string $org_unit_id
	 *
	 * @return IOrgUnitMembershipDTO
	 */
	public function setOrgUnitId($org_unit_id): IOrgUnitMembershipDTO;


	/**
	 * @return int
	 */
	public function getOrgUnitIdType(): int;


	/**
	 * @param int $org_unit_id_type
	 *
	 * @return IOrgUnitMembershipDTO
	 */
	public function setOrgUnitIdType(int $org_unit_id_type): IOrgUnitMembershipDTO;


	/**
	 * @return int
	 */
	public function getUserId(): int;


	/**
	 * @param int $user_id
	 *
	 * @return IOrgUnitMembershipDTO
	 */
	public function setUserId(int $user_id): IOrgUnitMembershipDTO;


	/**
	 * @return int
	 */
	public function getPosition(): int;


	/**
	 * @param int $position
	 *
	 * @return IOrgUnitMembershipDTO
	 */
	public function setPosition(int $position): IOrgUnitMembershipDTO;
}
