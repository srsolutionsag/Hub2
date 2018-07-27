<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

/**
 * Class OriginSyncSummaryFactory
 *
 * @package SRAG\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryFactory implements IOriginSyncSummaryFactory {

	/**
	 * @inheritDoc
	 */
	public function common() {
		return $this->web();
	}


	/**
	 * @inheritDoc
	 */
	public function web() {
		return new OriginSyncSummaryWeb();
	}


	/**
	 * @inheritDoc
	 */
	public function cron() {
		return new OriginSyncSummaryCron();
	}


	/**
	 * @inheritDoc
	 */
	public function mail() {
		return new OriginSyncSummaryCron();
	}
}
