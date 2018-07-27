<?php

namespace SRAG\Plugins\Hub2\Object\OrgUnit;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IOrgUnitDTO
 *
 * @package SRAG\Plugins\Hub2\Object\OrgUnit
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitDTO extends IDataTransferObject {

	/**
	 * @var int
	 */
	const PARENT_ID_TYPE_REF_ID = 1;
	/**
	 * @var int
	 */
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;


	/**
	 * @return string
	 */
	public function getTitle(): string;


	/**
	 * @param string $title
	 *
	 * @return IOrgUnitDTO
	 */
	public function setTitle(string $title): IOrgUnitDTO;


	/**
	 * @return string
	 */
	public function getDescription(): string;


	/**
	 * @param string $description
	 *
	 * @return IOrgUnitDTO
	 */
	public function setDescription(string $description): IOrgUnitDTO;


	/**
	 * @return int
	 */
	public function getOwner(): int;


	/**
	 * @param int $owner
	 *
	 * @return IOrgUnitDTO
	 */
	public function setOwner(int $owner): IOrgUnitDTO;


	/**
	 * @return int|string|null
	 */
	public function getParentId();


	/**
	 * @param int|string|null $parent_id
	 *
	 * @return IOrgUnitDTO
	 */
	public function setParentId($parent_id): IOrgUnitDTO;


	/**
	 * @return int
	 */
	public function getParentIdType(): int;


	/**
	 * @param int $parent_id_type
	 *
	 * @return IOrgUnitDTO
	 */
	public function setParentIdType(int $parent_id_type): IOrgUnitDTO;


	/**
	 * @return string
	 */
	public function getOrgUnitType(): string;


	/**
	 * @param string $org_unit_type
	 *
	 * @return IOrgUnitDTO
	 */
	public function setOrgUnitType(string $org_unit_type): IOrgUnitDTO;


	/**
	 * @return string
	 */
	public function getExtId(): string;


	/**
	 * @param string $ext_id
	 *
	 * @return IOrgUnitDTO
	 */
	public function setExtId(string $ext_id): IOrgUnitDTO;
}
