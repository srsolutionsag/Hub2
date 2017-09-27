<?php namespace SRAG\Hub2\Object;

use SRAG\Hub2\Object\Category\CategoryDTO;
use SRAG\Hub2\Object\Course\CourseDTO;
use SRAG\Hub2\Object\User\UserDTO;

/**
 * Interface IDataTransferObjectFactory
 *
 * @package SRAG\Hub2\Object
 */
interface IDataTransferObjectFactory {

	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Hub2\Object\User\UserDTO
	 */
	public function user($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Hub2\Object\Course\CourseDTO
	 */
	public function course($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Hub2\Object\Category\CategoryDTO
	 */
	public function category($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return GroupD
	 */
	public function group($ext_id);


	/**
	 * @param string $ext_id
	 *
	 * @return \SRAG\Hub2\Object\Session\SessionDTO
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