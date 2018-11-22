<?php

namespace srag\Plugins\Hub2\Sync\Summary;

/**
 * Class OriginSyncSummaryFactory
 *
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryFactory implements IOriginSyncSummaryFactory {

	/**
	 * @inheritdoc
	 */
	public function common() {
		return $this->web();
	}


	/**
	 * @inheritdoc
	 */
	public function web() {
		return new OriginSyncSummaryWeb();
	}


	/**
	 * @inheritdoc
	 */
	public function cron() {
		return new OriginSyncSummaryCron();
	}


	/**
	 * @inheritdoc
	 */
	public function mail() {
		return new OriginSyncSummaryCron();
	}
}
