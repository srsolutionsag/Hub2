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
	 * @return string
	 */
	public function getExtId(): string;


	/**
	 * @param string $extId
	 *
	 * @return IOrgUnitMembershipDTO
	 */
	public function setExtId(string $extId): IOrgUnitMembershipDTO;
}
