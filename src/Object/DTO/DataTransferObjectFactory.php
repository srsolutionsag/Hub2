<?php

namespace srag\Plugins\Hub2\Object\DTO;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnit\OrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\OrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class DataTransferObjectFactory
 *
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DataTransferObjectFactory implements IDataTransferObjectFactory {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


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
	public function courseMembership($course_id, $user_id) {
		return new CourseMembershipDTO($course_id, $user_id);
	}


	/**
	 * @inheritdoc
	 */
	public function groupMembership($group_id, $user_id) {
		return new GroupMembershipDTO($group_id, $user_id);
	}


	/**
	 * @inheritDoc
	 */
	public function sessionMembership($session_id, $user_id) {
		return new SessionMembershipDTO($session_id, $user_id);
	}


	/**
	 * @inheritdoc
	 */
	public function orgUnit(string $ext_id): IOrgUnitDTO {
		return new OrgUnitDTO($ext_id);
	}


	/**
	 * @inheritdoc
	 */
	public function orgUnitMembership($org_unit_id, int $user_id, int $position): IOrgUnitMembershipDTO {
		return new OrgUnitMembershipDTO($org_unit_id, $user_id, $position);
	}
}
