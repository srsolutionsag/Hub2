<?php

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Category\ICategory;
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
 *
 * @package srag\Plugins\Hub2\Object
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
