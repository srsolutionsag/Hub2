<?php

namespace srag\Plugins\Hub2\Sync\Summary;

use srag\Plugins\Hub2\Object\IObject;
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
	protected function renderOneSync(IOriginSync $originSync): string {
		// Print out some useful statistics: --> Should maybe be a OriginSyncSummary object
		$msg = self::plugin()->translate("summary_for", "", [ $originSync->getOrigin()->getTitle() ]) . "\n**********\n";
		$msg .= self::plugin()->translate("summary_delivered_data_sets", "", [ $originSync->getCountDelivered() ]) . "\n";
		$msg .= self::plugin()->translate("summary_failed", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_FAILED) ]) . "\n";
		$msg .= self::plugin()->translate("summary_created", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_CREATED) ]) . "\n";
		$msg .= self::plugin()->translate("summary_updated", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_UPDATED) ]) . "\n";
		$msg .= self::plugin()->translate("summary_outdated", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_OUTDATED) ]) . "\n";
		$msg .= self::plugin()->translate("summary_ignored", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_IGNORED) ]) . "\n";

		$msg .= self::plugin()->translate("summary_logs") . "\n**********\n";
		if (count($originSync->getLogs()) > 0) {
			// Only output one exception on web because it's to large for Session (ilUtil::sendInfo)
			$exception = current($originSync->getLogs());
			$msg .= $exception->getMessage() . "\n";

			if (count($originSync->getLogs()) > 1) {
				$msg .= self::plugin()->translate("summary_logs_more", "", [ (count($originSync->getLogs()) - 1) ]) . "\n";
			}
		}
		$msg = rtrim($msg, "\n");

		return $msg;
	}
}
