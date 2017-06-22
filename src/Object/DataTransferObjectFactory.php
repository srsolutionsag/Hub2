<?php namespace SRAG\Hub2\Object;
//use SRAG\Hub2\Origin\IOrigin;

/**
 * Class ObjectDTOFactory
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Object
 */
class DataTransferObjectFactory implements IDataTransferObjectFactory {

	/**
	 * @inheritdoc
	 */
	public function user($ext_id) {
		return new UserDTO($ext_id);
	}

	public function course($ext_id) {
		// TODO: Implement course() method.
	}

	public function category($ext_id) {
		// TODO: Implement category() method.
	}

	public function group($ext_id) {
		// TODO: Implement group() method.
	}

	public function session($ext_id) {
		// TODO: Implement session() method.
	}

	public function courseMembership($ext_course_id, $ext_user_id) {
		// TODO: Implement courseMembership() method.
	}

	public function groupMembership($ext_group_id, $ext_user_id) {
		// TODO: Implement groupMembership() method.
	}
}