<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

use SRAG\Plugins\Hub2\Object\IObject;
use SRAG\Plugins\Hub2\Sync\IOriginSync;

/**
 * Class OriginSyncSummaryCron
 *
 * @package SRAG\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryCron extends OriginSyncSummaryBase implements IOriginSyncSummary {

	/**
	 * @inheritDoc
	 */
	public function getOutputAsString() {
		$return = "";
		foreach ($this->syncs as $sync) {
			$return .= $this->renderOneSync($sync) . "\n\n";
		}

		return $return;
	}


	/**
	 * @param IOriginSync $originSync
	 *
	 * @return string
	 */
	private function renderOneSync(IOriginSync $originSync) {
		// Print out some useful statistics: --> Should maybe be a OriginSyncSummary object
		$msg = self::translate("summary_for", "", [ $originSync->getOrigin()->getTitle() ]) . "\n**********\n";
		$msg .= self::translate("summary_delivered_data_sets", "", [ $originSync->getCountDelivered() ]) . "\n";
		$msg .= self::translate("summary_created", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_CREATED) ]) . "\n";
		$msg .= self::translate("summary_updated", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_UPDATED) ]) . "\n";
		$msg .= self::translate("summary_deleted", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_DELETED) ]) . "\n";
		$msg .= self::translate("summary_ignored", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_IGNORED) ]) . "\n";
		$msg .= self::translate("summary_no_changes", "", [ $originSync->getCountProcessedByStatus(IObject::STATUS_NOTHING_TO_UPDATE) ]) . "\n\n";
		foreach ($originSync->getNotifications()->getMessages() as $context => $messages) {
			$msg .= "$context: \n**********\n";
			foreach ($messages as $message) {
				$msg .= "$message\n";
			}
			$msg .= "\n";
		}
		foreach ($originSync->getExceptions() as $exception) {
			$msg .= self::translate("summary_exceptions") . "\n**********\n";
			$msg .= $exception->getMessage() . "\n\n";
		}
		$msg = rtrim($msg, "\n");

		return $msg;
	}


	/**
	 * @inheritDoc
	 */
	public function getSummaryOfOrigin(IOriginSync $originSync) {
		return $this->renderOneSync($originSync);
	}
}
