<?php namespace SRAG\Hub2\Object;

//use SRAG\Hub2\Origin\IOrigin;

/**
 * Class ObjectDTOFactory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Object
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
		// TODO: Implement group() method.
	}

	/**
	 * @inheritdoc
	 */
	public function session($ext_id) {
		// TODO: Implement session() method.
	}

	/**
	 * @inheritdoc
	 */
	public function courseMembership($ext_course_id, $ext_user_id) {
		// TODO: Implement courseMembership() method.
	}

	/**
	 * @inheritdoc
	 */
	public function groupMembership($ext_group_id, $ext_user_id) {
		// TODO: Implement groupMembership() method.
	}
}