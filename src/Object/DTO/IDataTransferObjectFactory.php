<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagementDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Interface IDataTransferObjectFactory
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IDataTransferObjectFactory
{
    /**
     * @param string $ext_id
     * @return UserDTO
     */
    public function user($ext_id);

    /**
     * @param string $ext_id
     * @return CourseDTO
     */
    public function course($ext_id);

    /**
     * @param string $ext_id
     * @return CategoryDTO
     */
    public function category($ext_id);

    /**
     * @param string $ext_id
     * @return GroupDTO
     */
    public function group($ext_id);

    /**
     * @param string $ext_id
     * @return SessionDTO
     */
    public function session($ext_id);

    /**
     * @param int $course_id
     * @param int $user_id
     * @return CourseMembershipDTO
     */
    public function courseMembership($course_id, $user_id);

    /**
     * @param int $group_id
     * @param int $user_id
     * @return GroupMembershipDTO
     */
    public function groupMembership($group_id, $user_id);

    /**
     * @param int $session_id
     * @param int $user_id
     * @return SessionMembershipDTO
     */
    public function sessionMembership($session_id, $user_id);

    public function orgUnit(string $ext_id) : IOrgUnitDTO;

    public function orgUnitMembership(string $org_unit_id, int $user_id, int $position) : IOrgUnitMembershipDTO;

    public function competenceManagement(string $ext_id) : ICompetenceManagementDTO;
}
