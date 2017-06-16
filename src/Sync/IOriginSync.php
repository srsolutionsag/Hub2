<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync;

use SRAG\ILIAS\Plugins\Exception\AbortOriginSyncException;
use SRAG\ILIAS\Plugins\Exception\AbortSyncException;
use SRAG\ILIAS\Plugins\Exception\BuildObjectsFailedException;
use SRAG\ILIAS\Plugins\Exception\ConnectionFailedException;
use SRAG\ILIAS\Plugins\Exception\ParseDataFailedException;

/**
 * Interface ISync
 * @package SRAG\ILIAS\Plugins\Hub2\Sync
 */
interface IOriginSync {

	/**
	 * Execute the synchronization for the origin
	 *
	 * @throws ConnectionFailedException
	 * @throws ParseDataFailedException
	 * @throws BuildObjectsFailedException
	 * @throws AbortOriginSyncException
	 * @throws AbortSyncException
	 */
	public function execute();

	/**
	 * @return array
	 */
	public function getExceptions();
}