<?php

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Origin\IOrigin;

/**
 * Interface IGlobalHook
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Timon Amstutz
 */
interface IGlobalHook {

	/**
	 * This is executed before all active origins are synced.
     *
     * @param IOrigin[] $origins all active origins that will be exectued
     * @return bool If returned false, the sync is aborted.
	 */
	public function beforeSync( $active_orgins) :bool;


    /**
     * This is executed after all active origins have been.
     *
     * @param IOrigin[] $origins all active origins that have been executed.
	 */
	public function afterSync($active_orgins);

    /**
     * This is executed after afterSync and allows the custom processing of exceptions fired during the sync.
     * @param \Exception[] $exceptions
     */
    public function handleExceptions($exceptions);
}
