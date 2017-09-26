<?php namespace SRAG\Hub2\Sync;

/**
 * Interface ISync
 *
 * @package SRAG\Hub2\Sync
 */
interface ISync {

	/**
	 * Execute a sync over all active origins
	 *
	 * @return mixed
	 */
	public function execute();


	/**
	 * Collects the exceptions from all syncs over all origins
	 *
	 * @return array
	 */
	public function getExceptions();
}