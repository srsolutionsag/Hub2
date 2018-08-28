<?php

namespace SRAG\Plugins\Hub2\Object;

use ActiveRecord;
use SRAG\Plugins\Hub2\Object\Category\ARCategory;
use SRAG\Plugins\Hub2\Object\Category\ICategory;
use SRAG\Plugins\Hub2\Object\Course\ARCourse;
use SRAG\Plugins\Hub2\Object\Course\ICourse;
use SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use SRAG\Plugins\Hub2\Object\CourseMembership\ICourseMembership;
use SRAG\Plugins\Hub2\Object\Group\ARGroup;
use SRAG\Plugins\Hub2\Object\Group\IGroup;
use SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use SRAG\Plugins\Hub2\Object\GroupMembership\IGroupMembership;
use SRAG\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use SRAG\Plugins\Hub2\Object\OrgUnit\IOrgUnit;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembership;
use SRAG\Plugins\Hub2\Object\Session\ARSession;
use SRAG\Plugins\Hub2\Object\Session\ISession;
use SRAG\Plugins\Hub2\Object\SessionMembership\ISessionMembership;
use SRAG\Plugins\Hub2\Object\User\ARUser;
use SRAG\Plugins\Hub2\Object\User\IUser;

/**
 * Interface IObjectFactory
 *
 * @package SRAG\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObjectFactory {

	/**
	 * @param string $ext_id
	 *
	 * @return ActiveRecord|ARCategory|ICategory|ARCourse|ICourse|ARCourseMembership|ICourseMembership|ARGroup|IGroup|ARGroupMembership|IGroupMembership|ARSession|ISession|ARUser|IUser|IOrgUnit|AROrgUnit|IOrgUnitMembership|AROrgUnitMembership
	 */
	public function undefined($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return IUser
	 */
	public function user($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return ICourse
	 */
	public function course($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return ICategory
	 */
	public function category($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return IGroup
	 */
	public function group($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return ISession
	 */
	public function session($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return ICourseMembership
	 */
	public function courseMembership($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return IGroupMembership
	 */
	public function groupMembership($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return ISessionMembership
	 */
	public function sessionMembership($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return IOrgUnit
	 */
	public function orgUnit(string $ext_id): IOrgUnit;


	/**
	 * @param string $ext_id
	 *
	 * @return IOrgUnitMembership
	 */
	public function orgUnitMembership(string $ext_id): IOrgUnitMembership;
}
