<?php

namespace SRAG\Hub2\Sync\Processor;

/**
 * Interface ISyncProcessorFactory
 *
 * @package SRAG\Hub2\Sync\Processor
 */
interface ISyncProcessorFactory {

	/**
	 * @return \SRAG\Hub2\Sync\Processor\User\IUserSyncProcessor
	 */
	public function user();


	/**
	 * @return \SRAG\Hub2\Sync\Processor\Course\ICourseSyncProcessor
	 */
	public function course();


	/**
	 * @return \SRAG\Hub2\Sync\Processor\Category\ICategorySyncProcessor
	 */
	public function category();


	/**
	 * @return \SRAG\Hub2\Sync\Processor\Session\ISessionSyncProcessor
	 */
	public function session();
}