<?php

namespace SRAG\Plugins\Hub2\Origin;

/**
 * Interface IOriginFactory
 *
 * @package SRAG\Plugins\Hub2\Origin
 */
interface IOriginRepository {

	/**
	 * Returns all available origins in the correct order of the syncing process:
	 *
	 * Users > Categories > Courses > CourseMemberShips > Groups > GroupMemberships > Sessions
	 *
	 * @return IOrigin[]
	 */
	public function all();


	/**
	 * Same as all() without inactive origins
	 *
	 * @return IOrigin[]
	 */
	public function allActive();


	/**
	 * Returns the origins of object type user
	 *
	 * @return \SRAG\Plugins\Hub2\Origin\User\IUserOrigin[]
	 */
	public function users();


	/**
	 * @return \SRAG\Plugins\Hub2\Origin\Course\ICourseOrigin[]
	 */
	public function courses();


	/**
	 * @return \SRAG\Plugins\Hub2\Origin\Category\ICategoryOrigin[]
	 */
	public function categories();


	/**
	 * @return \SRAG\Plugins\Hub2\Origin\CourseMembership\ICourseMembershipOrigin[]
	 */
	public function courseMemberships();


	/**
	 * @return \SRAG\Plugins\Hub2\Origin\Group\IGroupOrigin[]
	 */
	public function groups();


	/**
	 * @return \SRAG\Plugins\Hub2\Origin\GroupMembership\IGroupMembershipOrigin[]
	 */
	public function groupMemberships();


	/**
	 * @return \SRAG\Plugins\Hub2\Origin\Session\ISessionOrigin[]
	 */
	public function sessions();


	/**
	 * @return \SRAG\Plugins\Hub2\Origin\SessionMembership\ISessionMembershipOrigin[]
	 */
	public function sessionsMemeberships();
}