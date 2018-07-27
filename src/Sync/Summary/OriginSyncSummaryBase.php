<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

use ilHub2Plugin;
use ilMimeMail;
use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Object\IObject;
use SRAG\Plugins\Hub2\Sync\IOriginSync;

/**
 * Class OriginSyncSummaryCron
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Sync\Summary
 */
abstract class OriginSyncSummaryBase implements IOriginSyncSummary {

	use DIC;
	/**
	 * @var IOriginSync[]
	 */
	protected $syncs = array();
	/**
	 * @var ilHub2Plugin
	 */
	protected $pl;


	/**
	 *
	 */
	public function __construct() {
		$this->pl = ilHub2Plugin::getInstance();
	}


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
	 * @param IOriginSync $originSync
	 *
	 * @return string
	 */
	private function renderOneSync(IOriginSync $originSync) {
		// Print out some useful statistics: --> Should maybe be a OriginSyncSummary object
		$msg = sprintf($this->pl->txt("summary_for"), $originSync->getOrigin()->getTitle()) . "\n**********\n";
		$msg .= sprintf($this->pl->txt("summary_delivered_data_sets"), $originSync->getCountDelivered()) . "\n";
		$msg .= sprintf($this->pl->txt("summary_created"), $originSync->getCountProcessedByStatus(IObject::STATUS_CREATED)) . "\n";
		$msg .= sprintf($this->pl->txt("summary_updated"), $originSync->getCountProcessedByStatus(IObject::STATUS_UPDATED)) . "\n";
		$msg .= sprintf($this->pl->txt("summary_deleted"), $originSync->getCountProcessedByStatus(IObject::STATUS_DELETED)) . "\n";
		$msg .= sprintf($this->pl->txt("summary_ignored"), $originSync->getCountProcessedByStatus(IObject::STATUS_IGNORED)) . "\n";
		$msg .= sprintf($this->pl->txt("summary_no_changes"), $originSync->getCountProcessedByStatus(IObject::STATUS_NOTHING_TO_UPDATE)) . "\n\n";
		foreach ($originSync->getNotifications()->getMessages() as $context => $messages) {
			$msg .= "$context: \n**********\n";
			foreach ($messages as $message) {
				$msg .= "$message\n";
			}
			$msg .= "\n";
		}
		foreach ($originSync->getExceptions() as $exception) {
			$msg .= $this->pl->txt("summary_exceptions") . "\n**********\n";
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


	/**
	 * @inheritDoc
	 */
	public function sendNotifications() {
		$mail = new ilMimeMail();
		$mail->From($this->mailMimeSenderFactory()->system());

		foreach ($this->syncs as $originSync) {
			$summary_email = $originSync->getOrigin()->config()->getNotificationsSummary();
			$error_email = $originSync->getOrigin()->config()->getNotificationsErrors();
			$title = $originSync->getOrigin()->getTitle();
			if ($summary_email) {
				$mail->Subject(sprintf($this->pl->txt("summary_notification"), $title));
				$mail->To($summary_email);
				$mail->Body($this->renderOneSync($originSync));
				$mail->Send();
			}
			if ($error_email && $originSync->getExceptions()) {
				$mail->To($error_email);
				$mail->Subject(sprintf($this->pl->txt("summary_exceptions_in"), $title));
				$msg = $this->pl->txt("summary_exceptions");
				foreach ($originSync->getExceptions() as $exception) {
					$msg .= "{$exception->getMessage()}\n";
					$msg .= sprintf($this->pl->txt("summary_in"), $exception->getFile()) . "\n";
				}
				$msg = rtrim($msg, "\n");

				$mail->Body($msg);
				$mail->Send();
			}
		}
	}
}
