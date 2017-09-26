<?php namespace SRAG\Hub2\Sync\Processor;

/**
 * Interface ISyncProcessorFactory
 *
 * @package SRAG\Hub2\Sync\Processor
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
}