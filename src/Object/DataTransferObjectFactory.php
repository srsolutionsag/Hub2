<?php

namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Object\Category\CategoryDTO;
use SRAG\Plugins\Hub2\Object\Course\CourseDTO;
use SRAG\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use SRAG\Plugins\Hub2\Object\Group\GroupDTO;
use SRAG\Plugins\Hub2\Object\Session\SessionDTO;
use SRAG\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ObjectDTOFactory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Object
 */
class DataTransferObjectFactory implements IDataTransferObjectFactory {

	/**
	 * @inheritdoc
	 */
	public function user($ext_id) {
		return new UserDTO($ext_id);
	}


	/**
	 * @inheritdoc
	 */
	public function course($ext_id) {
		return new CourseDTO($ext_id);
	}


	/**
	 * @inheritdoc
	 */
	public function category($ext_id) {
		return new CategoryDTO($ext_id);
	}


	/**
	 * @inheritdoc
	 */
	public function group($ext_id) {
		return new GroupDTO($ext_id);
	}


	/**
	 * @inheritdoc
	 */
	public function session($ext_id) {
		return new SessionDTO($ext_id);
	}


	/**
	 * @inheritdoc
	 */
	public function courseMembership($ext_course_id, $ext_user_id) {
		return new CourseMembershipDTO($ext_course_id, $ext_user_id);
	}


	/**
	 * @inheritdoc
	 */
	public function groupMembership($ext_group_id, $ext_user_id) {
		// TODO: Implement groupMembership() method.
	}
}