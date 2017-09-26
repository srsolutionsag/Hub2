<?php namespace SRAG\Hub2\Object;

/**
 * Interface IDataTransferObjectFactory
 *
 * @package SRAG\Hub2\Object
 */
interface IDataTransferObjectFactory {

	/**
	 * @param string $ext_id
	 *
	 * @return UserDTO
	 */
	public function user($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return CourseDTO
	 */
	public function course($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return CategoryDTO
	 */
	public function category($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return GroupDTO
	 */
	public function group($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return SessionDTO
	 */
	public function session($ext_id);


	/**
	 * @param string $ext_course_id , $ext_user_id
	 *
	 * @return CourseMembershipDTO
	 */
	public function courseMembership($ext_course_id, $ext_user_id);


	/**
	 * @param string $ext_group_id , $ext_user_id
	 *
	 * @return GroupMembershipDTO
	 */
	public function groupMembership($ext_group_id, $ext_user_id);
}