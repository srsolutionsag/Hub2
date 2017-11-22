<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

use SRAG\Plugins\Hub2\Object\IObject;
use SRAG\Plugins\Hub2\Sync\IOriginSync;

/**
 * Class OriginSyncSummaryCron
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryCron extends OriginSyncSummaryBase implements IOriginSyncSummary {

	/**
	 * @var IOriginSync[]
	 */
	protected $syncs;


	/**
	 * @inheritDoc
	 */
	public function addOriginSync(IOriginSync $originSync) {
		$this->syncs[] = $originSync;
	}


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
	 * @param \SRAG\Plugins\Hub2\Sync\IOriginSync $originSync
	 *
	 * @return string
	 */
	private function renderOneSync(IOriginSync $originSync) {
		// Print out some useful statistics: --> Should maybe be a OriginSyncSummary object
		$msg = "Summary for {$originSync->getOrigin()->getTitle()}:\n**********\n";
		$msg .= "Delivered data sets: " . $originSync->getCountDelivered() . "\n";
		$msg .= "Created: " . $originSync->getCountProcessedByStatus(IObject::STATUS_CREATED)
		        . "\n";
		$msg .= "Updated: " . $originSync->getCountProcessedByStatus(IObject::STATUS_UPDATED)
		        . "\n";
		$msg .= "Deleted: " . $originSync->getCountProcessedByStatus(IObject::STATUS_DELETED)
		        . "\n";
		$msg .= "Ignored: " . $originSync->getCountProcessedByStatus(IObject::STATUS_IGNORED)
		        . "\n";
		$msg .= "No Changes: "
		        . $originSync->getCountProcessedByStatus(IObject::STATUS_NOTHING_TO_UPDATE)
		        . "\n\n";
		foreach ($originSync->getNotifications()->getMessages() as $context => $messages) {
			$msg .= "$context:\n**********\n";
			foreach ($messages as $message) {
				$msg .= "$message\n";
			}
			$msg .= "\n";
		}
		foreach ($originSync->getExceptions() as $exception) {
			$msg .= "Exceptions:\n**********\n";
			$msg .= $exception->getMessage() . "\n";
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

