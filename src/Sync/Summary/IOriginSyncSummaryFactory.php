<?php

namespace SRAG\Hub2\Sync\Summary;

/**
 * Interface IOriginSyncSummaryFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummaryFactory {

	/**
	 * @return \SRAG\Hub2\Sync\Summary\IOriginSyncSummary
	 */
	public function common();


	/**
	 * @return \SRAG\Hub2\Sync\Summary\IOriginSyncSummary
	 */
	public function web();


	/**
	 * @return \SRAG\Hub2\Sync\Summary\IOriginSyncSummary
	 */
	public function cron();
}