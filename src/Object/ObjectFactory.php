<?php

namespace SRAG\Plugins\Hub2\Object;

use LogicException;
use SRAG\Plugins\Hub2\Object\Category\ARCategory;
use SRAG\Plugins\Hub2\Object\Course\ARCourse;
use SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use SRAG\Plugins\Hub2\Object\Group\ARGroup;
use SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use SRAG\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use SRAG\Plugins\Hub2\Object\OrgUnit\IOrgUnit;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembership;
use SRAG\Plugins\Hub2\Object\Session\ARSession;
use SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use SRAG\Plugins\Hub2\Object\User\ARUser;
use SRAG\Plugins\Hub2\Origin\IOrigin;

/**
 * Class ObjectFactory
 *
 * @package SRAG\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ObjectFactory implements IObjectFactory {

	/**
	 * @var IOrigin
	 */
	protected $origin;


	/**
	 * @param IOrigin $origin
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
	}


	/**
	 * @inheritdoc
	 */
	public function undefined($ext_id) {
		switch ($this->origin->getObjectType()) {
			case IOrigin::OBJECT_TYPE_USER:
				return $this->user($ext_id);
			case IOrigin::OBJECT_TYPE_COURSE:
				return $this->course($ext_id);
			case IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP:
				return $this->courseMembership($ext_id);
			case IOrigin::OBJECT_TYPE_CATEGORY:
				return $this->category($ext_id);
			case IOrigin::OBJECT_TYPE_GROUP:
				return $this->group($ext_id);
			case IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP:
				return $this->groupMembership($ext_id);
			case IOrigin::OBJECT_TYPE_SESSION:
				return $this->session($ext_id);
			case IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP:
				return $this->sessionMembership($ext_id);
			case IOrigin::OBJECT_TYPE_ORGNUNIT;
				return $this->orgUnit($ext_id);
			case IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP;
				return $this->orgUnitMembership($ext_id);
			default:
				throw new LogicException('no object-type for this origin found');
		}
	}


	/**
	 * @inheritdoc
	 */
	public function user($ext_id) {
		$user = ARUser::find($this->getId($ext_id));
		if ($user === NULL) {
			$user = new ARUser();
			$user->setOriginId($this->origin->getId());
			$user->setExtId($ext_id);
		}

		return $user;
	}


	/**
	 * @inheritdoc
	 */
	public function course($ext_id) {
		$course = ARCourse::find($this->getId($ext_id));
		if ($course === NULL) {
			$course = new ARCourse();
			$course->setOriginId($this->origin->getId());
			$course->setExtId($ext_id);
		}

		return $course;
	}


	/**
	 * @inheritdoc
	 */
	public function category($ext_id) {
		$category = ARCategory::find($this->getId($ext_id));
		if ($category === NULL) {
			$category = new ARCategory();
			$category->setOriginId($this->origin->getId());
			$category->setExtId($ext_id);
		}

		return $category;
	}


	/**
	 * @inheritdoc
	 */
	public function group($ext_id) {
		$group = ARGroup::find($this->getId($ext_id));
		if ($group === NULL) {
			$group = new ARGroup();
			$group->setOriginId($this->origin->getId());
			$group->setExtId($ext_id);
		}

		return $group;
	}


	/**
	 * @inheritdoc
	 */
	public function session($ext_id) {
		$session = ARSession::find($this->getId($ext_id));
		if ($session === NULL) {
			$session = new ARSession();
			$session->setOriginId($this->origin->getId());
			$session->setExtId($ext_id);
		}

		return $session;
	}


	/**
	 * @inheritdoc
	 */
	public function courseMembership($ext_id) {
		$course_membership = ARCourseMembership::find($this->getId($ext_id));
		if ($course_membership === NULL) {
			$course_membership = new ARCourseMembership();
			$course_membership->setOriginId($this->origin->getId());
			$course_membership->setExtId($ext_id);
		}

		return $course_membership;
	}


	/**
	 * @inheritdoc
	 */
	public function groupMembership($ext_id) {
		$group_membership = ARGroupMembership::find($this->getId($ext_id));
		if ($group_membership === NULL) {
			$group_membership = new ARGroupMembership();
			$group_membership->setOriginId($this->origin->getId());
			$group_membership->setExtId($ext_id);
		}

		return $group_membership;
	}


	/**
	 * @inheritDoc
	 */
	public function sessionMembership($ext_id) {
		$session_membership = ARSessionMembership::find($this->getId($ext_id));
		if ($session_membership === NULL) {
			$session_membership = new ARSessionMembership();
			$session_membership->setOriginId($this->origin->getId());
			$session_membership->setExtId($ext_id);
		}

		return $session_membership;
	}


	/**
	 * @inheritDoc
	 */
	public function orgUnit(string $ext_id): IOrgUnit {
		$org_unit = AROrgUnit::find($this->getId($ext_id));
		if ($org_unit === NULL) {
			$org_unit = new AROrgUnit();
			$org_unit->setOriginId($this->origin->getId());
			$org_unit->setExtId($ext_id);
		}

		return $org_unit;
	}


	/**
	 * @inheritDoc
	 */
	public function orgUnitMembership(string $ext_id): IOrgUnitMembership {
		$org_unit_membership = AROrgUnitMembership::find($this->getId($ext_id));
		if ($org_unit_membership === NULL) {
			$org_unit_membership = new AROrgUnitMembership();
			$org_unit_membership->setOriginId($this->origin->getId());
			$org_unit_membership->setExtId($ext_id);
		}

		return $org_unit_membership;
	}


	/**
	 * Get the primary ID of an object. In the ActiveRecord implementation, the primary key is a
	 * concatenation of the origins ID with the external-ID, see IObject::create()
	 *
	 * @param string $ext_id
	 *
	 * @return string
	 */
	protected function getId($ext_id) {
		return $this->origin->getId() . $ext_id;
	}
}
