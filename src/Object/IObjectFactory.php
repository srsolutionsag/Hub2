<?php

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Category\ICategory;
use srag\Plugins\Hub2\Object\CompetenceManagement\ARCompetenceManagement;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagement;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\Course\ICourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\CourseMembership\ICourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\Group\IGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\GroupMembership\IGroupMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\Session\ISession;
use srag\Plugins\Hub2\Object\SessionMembership\ISessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Object\User\IUser;

/**
 * Interface IObjectFactory
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObjectFactory
{
    /**
     * Get the primary ID of an object. In the ActiveRecord implementation, the primary key is a
     * concatenation of the origins ID with the external-ID, see IObject::create()
     * @param string $ext_id
     * @return string
     */
    public function getId($ext_id);

    /**
     * @param string $ext_id
     * @return ActiveRecord|ARCategory|ICategory|ARCourse|ICourse|ARCourseMembership|ICourseMembership|ARGroup|IGroup|ARGroupMembership|IGroupMembership|ARSession|ISession|ARUser|IUser|IOrgUnit|AROrgUnit|IOrgUnitMembership|AROrgUnitMembership|ICompetenceManagement|ArCompetenceManagement
     */
    public function undefined($ext_id);

    /**
     * @param string $ext_id
     * @return IUser
     */
    public function user($ext_id);

    /**
     * @param string $ext_id
     * @return ICourse
     */
    public function course($ext_id);

    /**
     * @param string $ext_id
     * @return ICategory
     */
    public function category($ext_id);

    /**
     * @param string $ext_id
     * @return IGroup
     */
    public function group($ext_id);

    /**
     * @param string $ext_id
     * @return ISession
     */
    public function session($ext_id);

    /**
     * @param string $ext_id
     * @return ICourseMembership
     */
    public function courseMembership($ext_id);

    /**
     * @param string $ext_id
     * @return IGroupMembership
     */
    public function groupMembership($ext_id);

    /**
     * @param string $ext_id
     * @return ISessionMembership
     */
    public function sessionMembership($ext_id);

    public function orgUnit(string $ext_id) : IOrgUnit;

    public function orgUnitMembership(string $ext_id) : IOrgUnitMembership;

    public function competenceManagement(string $ext_id) : ICompetenceManagement;

    /**
     * @return IUser[]
     */
    public function users() : array;

    public function usersExtIds() : array;

    /**
     * @return ICourse[]
     */
    public function courses() : array;

    public function coursesExtIds() : array;

    /**
     * @return ICategory[]
     */
    public function categories() : array;

    public function categoriesExtIds() : array;

    /**
     * Since there are places where the code makes the plural of a type
     * (such as 'user'), in case of 'category' it leads to a call to categorys()
     * which is – indeed – gramatically wrong but should just return the same as
     * @return ICategory[]
     * @see categories()
     */
    public function categorys() : array;

    public function categorysExtIds() : array;

    /**
     * @return IGroup[]
     */
    public function groups() : array;

    public function groupsExtIds() : array;

    /**
     * @return ISession[]
     */
    public function sessions() : array;

    public function sessionsExtIds() : array;

    /**
     * @return ICourseMembership[]
     */
    public function courseMemberships() : array;

    public function courseMembershipsExtIds() : array;

    /**
     * @return IGroupMembership[]
     */
    public function groupMemberships() : array;

    public function groupMembershipsExtIds() : array;

    /**
     * @return ISessionMembership[]
     */
    public function sessionMemberships() : array;

    public function sessionMembershipsExtIds() : array;

    /**
     * @return IOrgUnit[]
     */
    public function orgUnits() : array;

    public function orgUnitsExtIds() : array;

    /**
     * @return IOrgUnitMembership[]
     */
    public function orgUnitMemberships() : array;

    public function orgUnitMembershipsExtIds() : array;

    /**
     * @return ICompetenceManagement[]
     */
    public function competenceManagements() : array;

    public function competenceManagementsExtIds() : array;
}
