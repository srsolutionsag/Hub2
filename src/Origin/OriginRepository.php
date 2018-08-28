<?php

namespace SRAG\Plugins\Hub2\Origin;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Origin\Category\ARCategoryOrigin;
use SRAG\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use SRAG\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use SRAG\Plugins\Hub2\Origin\Group\ARGroupOrigin;
use SRAG\Plugins\Hub2\Origin\GroupMembership\ARGroupMembershipOrigin;
use SRAG\Plugins\Hub2\Origin\OrgUnit\AROrgUnitOrigin;
use SRAG\Plugins\Hub2\Origin\Session\ARSessionOrigin;
use SRAG\Plugins\Hub2\Origin\SessionMembership\ARSessionMembershipOrigin;
use SRAG\Plugins\Hub2\Origin\User\ARUserOrigin;

/**
 * Class OriginRepository
 *
 * @package SRAG\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginRepository implements IOriginRepository {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritdoc
	 */
	public function all() {
		return array_merge($this->users(), $this->categories(), $this->courses(), $this->courseMemberships(), $this->groups(), $this->groupMemberships(), $this->sessions(), $this->sessionsMemberships(), $this->orgUnits(), $this->orgUnitMemberships());
	}


	/**
	 * @inheritdoc
	 */
	public function allActive() {
		return array_filter($this->all(), function ($origin) {
			/** @var IOrigin $origin */
			return $origin->isActive();
		});
	}


	/**
	 * @inheritdoc
	 */
	public function users() {
		return ARUserOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_USER ])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function courses() {
		return ARCourseOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_COURSE ])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function categories() {
		return ARCategoryOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_CATEGORY ])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function courseMemberships() {
		return ARCourseMembershipOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP ])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function groups() {
		return ARGroupOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_GROUP ])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function groupMemberships() {
		return ARGroupMembershipOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP ])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function sessions() {
		return ARSessionOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_SESSION ])->get();
	}


	/**
	 * @inheritDoc
	 */
	public function sessionsMemberships() {
		return ARSessionMembershipOrigin::where([ 'object_type' => IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP ])->get();
	}


	/**
	 * @inheritDoc
	 */
	public function orgUnits(): array {
		return AROrgUnitOrigin::where([ "object_type" => IOrigin::OBJECT_TYPE_ORGNUNIT ])->get();
	}


	/**
	 * @inheritDoc
	 */
	public function orgUnitMemberships(): array {
		return AROrgUnitOrigin::where([ "object_type" => IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP ])->get();
	}
}
