<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync;
use SRAG\ILIAS\Plugins\Hub2\Origin\IOrigin;

/**
 * Interface ISync
 * @package SRAG\ILIAS\Plugins\Hub2\Sync
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