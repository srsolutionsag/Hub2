<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

/**
 * Interface IOriginSyncSummaryFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummaryFactory {

	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Summary\IOriginSyncSummary
	 */
	public function common();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Summary\IOriginSyncSummary
	 */
	public function web();


	/**
	 * @return \SRAG\Plugins\Hub2\Sync\Summary\IOriginSyncSummary
	 */
	public function cron();
}