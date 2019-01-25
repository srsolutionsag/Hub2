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
	public function web(): IOriginSyncSummary;


	/**
	 * @return IOriginSyncSummary
	 */
	public function mail(): IOriginSyncSummary;
}
