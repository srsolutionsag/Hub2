<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use SRAG\Plugins\Hub2\Sync\Processor\Category\ICategorySyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\Course\ICourseSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\CourseMembership\ICourseMembershipSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\Group\IGroupSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\GroupMembership\IGroupMembershipSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\OrgUnit\IOrgUnitSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\OrgUnitMembership\IOrgUnitMembershipSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\Session\ISessionSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\SessionMembership\ISessionMembershipSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\User\IUserSyncProcessor;

/**
 * Interface ISyncProcessorFactory
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISyncProcessorFactory {

	/**
	 * @return IUserSyncProcessor
	 */
	public function user();


	/**
	 * @return ICourseSyncProcessor
	 */
	public function course();


	/**
	 * @return ICategorySyncProcessor
	 */
	public function category();


	/**
	 * @return ISessionSyncProcessor
	 */
	public function session();


	/**
	 * @return ICourseMembershipSyncProcessor
	 */
	public function courseMembership();


	/**
	 * @return IGroupSyncProcessor
	 */
	public function group();


	/**
	 * @return IGroupMembershipSyncProcessor
	 */
	public function groupMembership();


	/**
	 * @return ISessionMembershipSyncProcessor
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
