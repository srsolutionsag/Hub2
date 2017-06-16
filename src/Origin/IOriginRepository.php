<?php namespace SRAG\ILIAS\Plugins\Hub2\Origin;

/**
 * Interface IOriginFactory
 * @package SRAG\ILIAS\Plugins\Hub2\Origin
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

}