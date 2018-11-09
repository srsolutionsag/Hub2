<?php

namespace srag\Plugins\Hub2\Sync\GlobalHook;

use Exception;
use srag\Plugins\Hub2\Origin\IOrigin;

/**
 * Interface IGlobalHook
 *
 * @package srag\Plugins\Hub2\Sync\GlobalHook
 * @author  Timon Amstutz
 */
interface IGlobalHook {

	/**
	 * This is executed before all active origins are synced.
	 *
	 * @param IOrigin[] $active_orgins all active origins that will be exectued
	 */
	public function beforeSync(array $active_orgins): bool;


	/**
	 * This is executed after all active origins have been.
	 *
	 * @param IOrigin[] $active_orgins all active origins that have been executed.
	 */
	public function afterSync(array $active_orgins): bool;


	/**
	 * This is executed after afterSync and allows the custom processing of exceptions fired during the sync.
	 *
	 * @param Exception[] $exceptions
	 */
	public function handleExceptions(array $exceptions): bool;
}
