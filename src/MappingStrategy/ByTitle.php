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
 * Class None
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ByTitle implements IMappingStrategy {

	/**
	 * @inheritDoc
	 */
	public function map(IDataTransferObject $dto): int {
		switch (true) {
			case ($dto instanceof UserDTO):
			case ($dto instanceof CourseMembershipDTO):
			case ($dto instanceof GroupMembershipDTO):
			case ($dto instanceof OrgUnitMembershipDTO):
				throw new HubException("Mapping using Title not supported for this type of DTO");
				break;
			case ($dto instanceof GroupDTO):
			case ($dto instanceof CourseDTO):
			case ($dto instanceof OrgUnitDTO):
			case ($dto instanceof CategoryDTO):
				if ($dto->getParentIdType() != CourseDTO::PARENT_ID_TYPE_REF_ID) {
					return 0;
				}
				global $DIC;

				$parent_id = $dto->getParentId();
				if (!\ilObject2::_exists($parent_id)) {
					return 0;
				}
				$children = $DIC->repositoryTree()->getChildsByType($parent_id, $this->getTypeByDTO($dto));

				foreach ($children as $child) {
					if ($child['title'] == $dto->getTitle()) {
						return (int)$child['ref_id'];
					}
				}
				break;
		}

		return 0;
	}


	private function getTypeByDTO(IDataTransferObject $dto): string {
		switch (true) {
			case ($dto instanceof GroupDTO):
				return "grp";
			case ($dto instanceof CourseDTO):
				return "crs";
			case ($dto instanceof OrgUnitDTO):
				return "orgu";
			case ($dto instanceof CategoryDTO):
				return "cat";
		}
	}
}
