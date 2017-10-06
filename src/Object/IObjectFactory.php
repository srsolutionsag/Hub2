<?php namespace SRAG\Hub2\Object;

/**
 * Interface IObjectFactory
 *
 * @package SRAG\Hub2\Object
 */
interface IObjectFactory {

	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Hub2\Object\User\IUser
	 */
	public function user($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Hub2\Object\Course\ICourse
	 */
	public function course($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Hub2\Object\Category\ICategory
	 */
	public function category($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Hub2\Object\Group\IGroup
	 */
	public function group($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Hub2\Object\Session\ISession
	 */
	public function session($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Hub2\Object\CourseMembership\ICourseMembership
	 */
	public function courseMembership($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Hub2\Object\GroupMembership\IGroupMembership
	 */
	public function groupMembership($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return \SRAG\Hub2\Object\SessionMembership\ISessionMembership
	 */
	public function sessionMembership($ext_id);
}