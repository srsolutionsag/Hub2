<?php namespace SRAG\Plugins\Hub2\Object\DTO;

use SRAG\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;

/**
 * Interface IDataTransferObjectFactory
 *
 * @package SRAG\Plugins\Hub2\Object
 */
interface IDataTransferObjectFactory {

	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\User\UserDTO
	 */
	public function user($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Course\CourseDTO
	 */
	public function course($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Category\CategoryDTO
	 */
	public function category($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Group\GroupDTO
	 */
	public function group($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\Session\SessionDTO
	 */
	public function session($ext_id);


	/**
	 * @param $course_id
	 * @param $user_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO
	 */
	public function courseMembership($course_id, $user_id);


	/**
	 * @param $group_id
	 * @param $user_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO
	 */
	public function groupMembership($group_id, $user_id);


	/**
	 * @param $session_id
	 * @param $user_id
	 *
	 * @return \SRAG\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO
	 */
	public function sessionMembership($session_id, $user_id);


	/**
	 * @param string $ext_id
	 *
	 * @return IOrgUnitDTO
	 */
	public function orgUnit(string $ext_id): IOrgUnitDTO;


	/**
	 * @param int $org_unit_id
	 * @param int $user_id
	 *
	 * @return IOrgUnitMembershipDTO
	 */
	public function orgUnitMembership(int $org_unit_id, int $user_id): IOrgUnitMembershipDTO;
}
