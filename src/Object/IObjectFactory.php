<?php

namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Object\OrgUnit\IOrgUnit;

/**
 * Interface IObjectFactory
 *
 * @package SRAG\Plugins\Hub2\Object
 */
interface IObjectFactory {

	/**
	 * @param string $ext_id
	 *
	 * @return \ActiveRecord|\SRAG\Plugins\Hub2\Object\Category\ARCategory|\SRAG\Plugins\Hub2\Object\Category\ICategory|\SRAG\Plugins\Hub2\Object\Course\ARCourse|\SRAG\Plugins\Hub2\Object\Course\ICourse|\SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership|\SRAG\Plugins\Hub2\Object\CourseMembership\ICourseMembership|\SRAG\Plugins\Hub2\Object\Group\ARGroup|\SRAG\Plugins\Hub2\Object\Group\IGroup|\SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership|\SRAG\Plugins\Hub2\Object\GroupMembership\IGroupMembership|\SRAG\Plugins\Hub2\Object\Session\ARSession|\SRAG\Plugins\Hub2\Object\Session\ISession|\SRAG\Plugins\Hub2\Object\User\ARUser|\SRAG\Plugins\Hub2\Object\User\IUser
	 */
	public function undefined($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\User\IUser
	 */
	public function user($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Course\ICourse
	 */
	public function course($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Category\ICategory
	 */
	public function category($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Group\IGroup
	 */
	public function group($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Session\ISession
	 */
	public function session($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\CourseMembership\ICourseMembership
	 */
	public function courseMembership($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\GroupMembership\IGroupMembership
	 */
	public function groupMembership($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\SessionMembership\ISessionMembership
	 */
	public function sessionMembership($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return IOrgUnit
	 */
	public function orgUnit(string $ext_id): IOrgUnit;
}
