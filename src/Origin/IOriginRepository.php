<?php

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Origin\Category\ICategoryOrigin;
use srag\Plugins\Hub2\Origin\CompetenceManagement\ICompetenceManagementOrigin;
use srag\Plugins\Hub2\Origin\Course\ICourseOrigin;
use srag\Plugins\Hub2\Origin\CourseMembership\ICourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Group\IGroupOrigin;
use srag\Plugins\Hub2\Origin\GroupMembership\IGroupMembershipOrigin;
use srag\Plugins\Hub2\Origin\OrgUnit\IOrgUnitOrigin;
use srag\Plugins\Hub2\Origin\OrgUnitMembership\IOrgUnitMembershipOrigin;
use srag\Plugins\Hub2\Origin\Session\ISessionOrigin;
use srag\Plugins\Hub2\Origin\SessionMembership\ISessionMembershipOrigin;
use srag\Plugins\Hub2\Origin\User\IUserOrigin;

/**
 * Interface IOriginFactory
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginRepository
{
    /**
     * Returns all available origins in the correct order of the syncing process:
     * Users > Categories > Courses > CourseMemberShips > Groups > GroupMemberships > Sessions
     * @return IOrigin[]
     */
    public function all();

    /**
     * Same as all() without inactive origins
     * @return IOrigin[]
     */
    public function allActive();

    /**
     * Returns the origins of object type user
     * @return IUserOrigin[]
     */
    public function users();

    /**
     * @return ICourseOrigin[]
     */
    public function courses();

    /**
     * @return ICategoryOrigin[]
     */
    public function categories();

    /**
     * @return ICourseMembershipOrigin[]
     */
    public function courseMemberships();

    /**
     * @return IGroupOrigin[]
     */
    public function groups();

    /**
     * @return IGroupMembershipOrigin[]
     */
    public function groupMemberships();

    /**
     * @return ISessionOrigin[]
     */
    public function sessions();

    /**
     * @return ISessionMembershipOrigin[]
     */
    public function sessionsMemberships();

    /**
     * @return IOrgUnitOrigin[]
     */
    public function orgUnits(): array;

    /**
     * @return IOrgUnitMembershipOrigin[]
     */
    public function orgUnitMemberships(): array;

    /**
     * @return ICompetenceManagementOrigin[]
     */
    public function competenceManagements(): array;
}
