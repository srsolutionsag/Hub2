<?php

namespace SRAG\Plugins\Hub2\Sync;

/**
 * Interface ISync
 *
 * @package SRAG\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
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
