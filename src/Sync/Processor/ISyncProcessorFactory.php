<?php namespace SRAG\Hub2\Sync\Processor;

/**
 * Interface ISyncProcessorFactory
 * @package SRAG\Hub2\Sync\Processor
 */
interface ISyncProcessorFactory {

	/**
	 * @return IUserSyncProcessor
	 */
	public function userProcessor();

	/**
	 * @return ICourseSyncProcessor
	 */
	public function courseProcessor();

	/**
	 * @return ICategorySyncProcessor
	 */
	public function categoryProcessor();

}