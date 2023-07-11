<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilObject2;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagementDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByTitle
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ByTitle extends AMappingStrategy implements IMappingStrategy
{
    /**
     * @var \ilTree
     */
    private $tree;

    public function __construct()
    {
        global $DIC;
        $this->tree = $DIC['tree'];
    }

    /**
     * @inheritdoc
     */
    public function map(IDataTransferObject $dto) : int
    {
        switch (true) {
            case ($dto instanceof UserDTO):
            case ($dto instanceof CourseMembershipDTO):
            case ($dto instanceof GroupMembershipDTO):
            case ($dto instanceof IOrgUnitMembershipDTO):
            case ($dto instanceof ICompetenceManagementDTO):
                throw new HubException("Mapping using Title not supported for this type of DTO");
            case ($dto instanceof IOrgUnitDTO):
                $orgu_ids = ilObject2::_getIdsForTitle($dto->getTitle(), 'orgu');
                foreach ($orgu_ids as $orgu_id) {
                    return $orgu_id;
                }
                break;
            case ($dto instanceof GroupDTO):
            case ($dto instanceof CourseDTO):
            case ($dto instanceof CategoryDTO):
                //case ($dto instanceof ICompetenceManagementDTO):
                if ($dto->getParentIdType() != CourseDTO::PARENT_ID_TYPE_REF_ID) {
                    return 0;
                }
                $parent_id = $dto->getParentId();
                if (!ilObject2::_exists($parent_id)) {
                    return 0;
                }
                $children = $this->tree->getChildsByType($parent_id, $this->getTypeByDTO($dto));

                foreach ($children as $child) {
                    if ($child['title'] == $dto->getTitle()) {
                        return (int) $child['ref_id'];
                    }
                }
                break;
        }

        return 0;
    }

    private function getTypeByDTO(\srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject $dto) : string
    {
        if ($dto instanceof GroupDTO) {
            return "grp";
        }
        if ($dto instanceof CourseDTO) {
            return "crs";
        }
        if ($dto instanceof IOrgUnitDTO) {
            return "orgu";
        }
        if ($dto instanceof CategoryDTO) {
            return "cat";
        }
        return '';
    }
}
