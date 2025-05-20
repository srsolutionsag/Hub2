<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
     * @return int
     */
    public function getTotalByStatus(int $status): ?int;

    /**
     * Get the number of objects processed by the sync.
     */
    public function getProcessedTotal(): int;

    /**
     * Get the amount of delivered data (excludes non-valid data).
     */
    public function getDeliveredTotal(): int;

    /**
     * Return the current origin
     */
    public function getOrigin(): IOrigin;
}
