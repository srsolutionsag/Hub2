<?php

namespace srag\Plugins\Hub2\Sync\Summary;

use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Sync\IOriginSync;

/**
 * Class OriginSyncSummaryCron
 *
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryMail extends OriginSyncSummaryBase implements IOriginSyncSummary {

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
		foreach ($originSync->getLogs() as $exception) {
			$msg .= $exception->getMessage() . "\n";
		}
		$msg = rtrim($msg, "\n");

		return $msg;
	}
}
