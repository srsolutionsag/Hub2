<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

/**
 * Interface IOriginSyncSummaryFactory
 *
 * @package SRAG\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummaryFactory {

	/**
	 * @return IOriginSyncSummary
	 */
	public function common();


	/**
	 * @return IOriginSyncSummary
	 */
	public function web();


	/**
	 * @return IOriginSyncSummary
	 */
	public function cron();
}
