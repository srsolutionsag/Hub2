<?php

namespace srag\Plugins\Hub2\Sync;

use Exception;
use srag\Plugins\Hub2\Exception\AbortOriginSyncException;
use srag\Plugins\Hub2\Exception\AbortSyncException;
use srag\Plugins\Hub2\Exception\BuildObjectsFailedException;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Exception\ParseDataFailedException;
use srag\Plugins\Hub2\Notification\OriginNotifications;
use srag\Plugins\Hub2\Origin\IOrigin;

/**
 * Interface ISync
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
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
	 * Get a collection of all exceptions occurred during executing the sync.
	 *
	 * @return Exception[]
	 */
	public function getExceptions();


	/**
	 * Get the number of objects processed by the final status, e.g.
	 *
	 *  * IObject::STATUS_CREATED: Number of objects created
	 *  * IObject::STATUS_UPDATED: Number of objects updated
	 *  * IObject::STATUS_DELETED: Number of objects deleted
	 *  * IObject::STATUS_IGNORED: Number of objects ignored
	 *
	 * @param int $status
	 *
	 * @return int
	 */
	public function getCountProcessedByStatus($status);


	/**
	 * Get the number of objects processed by the sync.
	 *
	 * @return int
	 */
	public function getCountProcessedTotal();


	/**
	 * Get the amount of delivered data (excludes non-valid data).
	 *
	 * @return int
	 */
	public function getCountDelivered();


	/**
	 * Get the notifications
	 *
	 * @return OriginNotifications
	 */
	public function getNotifications();


	/**
	 * Return the current origin
	 *
	 * @return IOrigin
	 */
	public function getOrigin();
}
