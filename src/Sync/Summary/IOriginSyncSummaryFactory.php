<?php

namespace srag\Plugins\Hub2\Sync\Summary;

/**
 * Interface IOriginSyncSummaryFactory
 *
 * @package srag\Plugins\Hub2\Sync\Summary
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
