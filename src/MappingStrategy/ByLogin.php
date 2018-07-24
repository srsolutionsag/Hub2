<?php namespace SRAG\Plugins\Hub2\MappingStrategy;

use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Object\Category\CategoryDTO;
use SRAG\Plugins\Hub2\Object\Course\CourseDTO;
use SRAG\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\Group\GroupDTO;
use SRAG\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use SRAG\Plugins\Hub2\Object\OrgUnit\OrgUnitDTO;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\OrgUnitMembershipDTO;
use SRAG\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByLogin
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ByLogin implements IMappingStrategy {

	/**
	 * @inheritDoc
	 */
	public function map(IDataTransferObject $dto): int {
		if (!$dto instanceof UserDTO) {
			throw new HubException("Mapping using Login not supported for this type of DTO");
		}

		return (int)\ilObjUser::getUserIdByLogin($dto->getLogin());
	}
}
