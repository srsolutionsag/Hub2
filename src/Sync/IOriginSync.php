<?php

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Origin\IOrigin;
use Throwable;
use srag\Plugins\Hub2\Jobs\Notifier;

/**
 * Interface IOriginSync
 * @package srag\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSync
{
    /**
     * Execute the synchronization for the origin
     * @throws Throwable
     */
    public function execute(Notifier $notifier);

    /**
     * Get the number of objects processed by the final status, e.g.
     *  * IObject::STATUS_CREATED: Number of objects created
     *  * IObject::STATUS_UPDATED: Number of objects updated
     *  * IObject::STATUS_OUTDATED: Number of objects deleted
     *  * IObject::STATUS_IGNORED: Number of objects ignored
     * @param int $status
     * @return int
     */
    public function getCountProcessedByStatus($status);

    /**
     * Get the number of objects processed by the sync.
     * @return int
     */
    public function getCountProcessedTotal();

    /**
     * Get the amount of delivered data (excludes non-valid data).
     * @return int
     */
    public function getCountDelivered();

    /**
     * Return the current origin
     * @return IOrigin
     */
    public function getOrigin();
}
