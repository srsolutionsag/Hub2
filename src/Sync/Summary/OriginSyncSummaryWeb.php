<?php

namespace srag\Plugins\Hub2\Sync\Summary;

use srag\Plugins\Hub2\Sync\IOriginSync;

/**
 * Class OriginSyncSummary
 *
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryWeb extends OriginSyncSummaryBase implements IOriginSyncSummary {

	/**
	 * @inheritdoc
	 */
	protected function renderOneSync(IOriginSync $originSync, bool $output_message = false): string {
		return parent::renderOneSync($originSync, false);
	}
}
