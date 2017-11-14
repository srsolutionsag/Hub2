<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

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
	 * @return mixed
	 */
	public function group();
}