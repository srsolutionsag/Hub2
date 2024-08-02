<?php

namespace srag\Plugins\Hub2\Object\DTO;

use ilHub2Plugin;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\CompetenceManagementDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagementDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnit\OrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\OrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class DataTransferObjectFactory
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DataTransferObjectFactory implements IDataTransferObjectFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


    public function user($ext_id): UserDTO
    {
        return new UserDTO($ext_id);
    }


    public function course($ext_id): CourseDTO
    {
        return new CourseDTO($ext_id);
    }


    public function category($ext_id): CategoryDTO
    {
        return new CategoryDTO($ext_id);
    }


    public function group($ext_id): GroupDTO
    {
        return new GroupDTO($ext_id);
    }


    public function session($ext_id): SessionDTO
    {
        return new SessionDTO($ext_id);
    }


    public function courseMembership(
        $course_id,
        $user_id
    ): CourseMembershipDTO {
        return new CourseMembershipDTO($course_id, $user_id);
    }


    public function groupMembership($group_id, $user_id): GroupMembershipDTO
    {
        return new GroupMembershipDTO($group_id, $user_id);
    }


    public function sessionMembership(
        $session_id,
        $user_id
    ): SessionMembershipDTO {
        return new SessionMembershipDTO($session_id, $user_id);
    }


    public function orgUnit(string $ext_id): IOrgUnitDTO
    {
        return new OrgUnitDTO($ext_id);
    }


    public function orgUnitMembership(string $org_unit_id, int $user_id, int $position): IOrgUnitMembershipDTO
    {
        return new OrgUnitMembershipDTO($org_unit_id, $user_id, $position);
    }


    public function competenceManagement(string $ext_id): ICompetenceManagementDTO
    {
        return new CompetenceManagementDTO($ext_id);
    }
}
