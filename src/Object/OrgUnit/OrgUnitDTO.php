<?php

namespace SRAG\Plugins\Hub2\Object\OrgUnit;

use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class OrgUnitDTO
 *
 * @package SRAG\Plugins\Hub2\Object\OrgUnit
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitDTO extends DataTransferObject implements IOrgUnitDTO {

	/**
	 * @var string
	 */
	protected $title = "";
	/**
	 * @var string
	 */
	protected $description = "";
	/**
	 * @var int
	 */
	protected $owner = 6;
	/**
	 * @var int|string|null
	 */
	protected $parent_id = null;
	/**
	 * @var int
	 */
	protected $parent_id_type = self::PARENT_ID_TYPE_REF_ID;
	/**
	 * @var string
	 */
	protected $org_unit_type = "";
	/**
	 * @var string
	 */
	protected $ext_id = "";


	/**
	 * @inheritDoc
	 */
	public function __construct(string $ext_id) {
		parent::__construct($ext_id);
		$this->ext_id = $ext_id;
	}


	/**
	 * @inheritdoc
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @inheritdoc
	 */
	public function setTitle(string $title): IOrgUnitDTO {
		$this->title = $title;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getDescription(): string {
		return $this->description;
	}


	/**
	 * @inheritdoc
	 */
	public function setDescription(string $description): IOrgUnitDTO {
		$this->description = $description;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getOwner(): int {
		return $this->owner;
	}


	/**
	 * @inheritdoc
	 */
	public function setOwner(int $owner): IOrgUnitDTO {
		$this->owner = $owner;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getParentId() {
		return $this->parent_id;
	}


	/**
	 * @inheritdoc
	 */
	public function setParentId($parent_id): IOrgUnitDTO {
		$this->parent_id = $parent_id;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getParentIdType(): int {
		return $this->parent_id_type;
	}


	/**
	 * @inheritdoc
	 */
	public function setParentIdType(int $parent__Id__type): IOrgUnitDTO {
		$this->parent_id_type = $parent__Id__type;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getOrgUnitType(): string {
		return $this->org_unit_type;
	}


	/**
	 * @inheritdoc
	 */
	public function setOrgUnitType(string $org_unit_type): IOrgUnitDTO {
		$this->org_unit_type = $org_unit_type;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getExtId(): string {
		return $this->ext_id;
	}


	/**
	 * @inheritdoc
	 */
	public function setExtId(string $ext_id): IOrgUnitDTO {
		$this->ext_id = $ext_id;

		return $this;
	}
}
