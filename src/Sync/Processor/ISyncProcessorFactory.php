<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use SRAG\Plugins\Hub2\Sync\Processor\OrgUnit\IOrgUnitSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\OrgUnitMembership\IOrgUnitMembershipSyncProcessor;

/**
 * Interface ISyncProcessorFactory
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor
 */
interface ISyncProcessorFactory {

	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\User\IUserSyncProcessor
	 */
	public function user();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\Course\ICourseSyncProcessor
	 */
	public function course();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\Category\ICategorySyncProcessor
	 */
	public function category();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\Session\ISessionSyncProcessor
	 */
	public function session();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\CourseMembership\ICourseMembershipSyncProcessor
	 */
	public function courseMembership();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\Group\IGroupSyncProcessor
	 */
	public function group();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\GroupMembership\IGroupMembershipSyncProcessor
	 */
	public function groupMembership();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Processor\SessionMembership\ISessionMembershipSyncProcessor
	 */
	public function sessionMembership();


	/**
	 * @return IOrgUnitSyncProcessor
	 */
	public function orgUnit(): IOrgUnitSyncProcessor;


	/**
	 * @return IOrgUnitMembershipSyncProcessor
	 */
	public function orgUnitMembership(): IOrgUnitMembershipSyncProcessor;
}
