<?php

namespace SRAG\Plugins\Hub2\Object\OrgUnit;

use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class OrgUnitDTO
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitDTO extends DataTransferObject {

	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $description;
	/**
	 * @var int
	 */
	protected $owner = 6;
	/**
	 * @var string
	 */
	private $parentId;
	/**
	 * @var int
	 */
	private $parentIdType = self::PARENT_ID_TYPE_REF_ID;
	/**
	 * @var string
	 */
	protected $orguType;
	/**
	 * @var string
	 */
	protected $extId;


	/**
	 * @inheritDoc
	 */
	public function __construct() {
		//TODO
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @param string $title
	 *
	 * @return OrgUnitDTO
	 */
	public function setTitle(string $title): OrgUnitDTO {
		$this->title = $title;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}


	/**
	 * @param string $description
	 *
	 * @return OrgUnitDTO
	 */
	public function setDescription(string $description): OrgUnitDTO {
		$this->description = $description;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getOwner(): int {
		return $this->owner;
	}


	/**
	 * @param int $owner
	 *
	 * @return OrgUnitDTO
	 */
	public function setOwner(int $owner): OrgUnitDTO {
		$this->owner = $owner;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getParentId(): string {
		return $this->parentId;
	}


	/**
	 * @param string $parentId
	 *
	 * @return OrgUnitDTO
	 */
	public function setParentId(string $parentId): OrgUnitDTO {
		$this->parentId = $parentId;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getParentIdType(): int {
		return $this->parentIdType;
	}


	/**
	 * @param int $parentIdType
	 *
	 * @return OrgUnitDTO
	 */
	public function setParentIdType(int $parentIdType): OrgUnitDTO {
		$this->parentIdType = $parentIdType;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getOrguType(): string {
		return $this->orguType;
	}


	/**
	 * @param string $orguType
	 *
	 * @return OrgUnitDTO
	 */
	public function setOrguType(string $orguType): OrgUnitDTO {
		$this->orguType = $orguType;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getExtId(): string {
		return $this->extId;
	}


	/**
	 * @param string $extId
	 *
	 * @return OrgUnitDTO
	 */
	public function setExtId(string $extId): OrgUnitDTO {
		$this->extId = $extId;

		return $this;
	}
}
