<?php

namespace SRAG\Hub2\Sync\Summary;

/**
 * Class OriginSyncSummaryFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryFactory implements IOriginSyncSummaryFactory {

	/**
	 * @inheritDoc
	 */
	public function common(): IOriginSyncSummary {
		return $this->web();
	}


	/**
	 * @inheritDoc
	 */
	public function web(): IOriginSyncSummary {
		return new OriginSyncSummaryWeb();
	}
}
