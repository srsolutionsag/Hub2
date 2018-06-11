<?php

namespace SRAG\Plugins\Hub2\Object\OrgUnitMembership;

use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class OrgUnitMembershipDTO
 *
 * @package SRAG\Plugins\Hub2\Object\OrgUnitMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitMembershipDTO extends DataTransferObject implements IOrgUnitMembershipDTO {

	/**
	 * @var string
	 */
	protected $ext_id;


	/**
	 * @inheritDoc
	 */
	public function __construct(string $ext_id) {
		parent::__construct($ext_id);
		$this->ext_id = $ext_id;
	}


	/**
	 * @return string
	 */
	public function getExtId(): string {
		return $this->ext_id;
	}


	/**
	 * @param string $ext_id
	 *
	 * @return IOrgUnitMembershipDTO
	 */
	public function setExtId(string $ext_id): IOrgUnitMembershipDTO {
		$this->ext_id = $ext_id;

		return $this;
	}
}
