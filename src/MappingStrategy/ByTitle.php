<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilObject2;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnit\OrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\OrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByTitle
 *
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ByTitle extends AMappingStrategy implements IMappingStrategy {

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
				$parent_id = $dto->getParentId();
				if (!ilObject2::_exists($parent_id)) {
					return 0;
				}
				$children = self::dic()->tree()->getChildsByType($parent_id, $this->getTypeByDTO($dto));

				foreach ($children as $child) {
					if ($child['title'] == $dto->getTitle()) {
						return (int)$child['ref_id'];
					}
				}
				break;
		}

		return 0;
	}


	/**
	 * @param IDataTransferObject $dto
	 *
	 * @return string
	 */
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

		return '';
	}
}
