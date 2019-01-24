<?php

namespace srag\Plugins\Hub2\Sync;

/**
 * Interface ISync
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISync {

	/**
	 * Execute a sync over all active origins
	 *
	 * @return mixed
	 */
	public function execute();
}
