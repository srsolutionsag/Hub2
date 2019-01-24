<?php

namespace srag\Plugins\Hub2\Sync\Summary;

use ilHub2Plugin;
use ilMimeMail;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Sync\IOriginSync;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class OriginSyncSummaryCron
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Sync\Summary
 */
abstract class OriginSyncSummaryBase implements IOriginSyncSummary {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IOriginSync[]
	 */
	protected $syncs = array();


	/**
	 *OriginSyncSummaryCron constructor
	 */
	public function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function addOriginSync(IOriginSync $originSync) {
		$this->syncs[] = $originSync;
	}


	/**
	 * @inheritdoc
	 */
	public function getOutputAsString() {
		$return = "";
		foreach ($this->syncs as $sync) {
			$return .= $this->renderOneSync($sync) . "\n\n";
		}

		return $return;
	}


	/**
	 * @inheritdoc
	 */
	public function sendNotifications() {
		$mail = new ilMimeMail();
		$mail->From(self::dic()->mailMimeSenderFactory()->system());

		foreach ($this->syncs as $originSync) {
			$summary_email = $originSync->getOrigin()->config()->getNotificationsSummary();
			$error_email = $originSync->getOrigin()->config()->getNotificationsErrors();
			$title = $originSync->getOrigin()->getTitle();
			if ($summary_email) {
				$mail->Subject(self::plugin()->translate("summary_notification", "", [ $title ]));
				$mail->To($summary_email);
				$mail->Body($this->renderOneSync($originSync));
				$mail->Send();
			}
			if ($error_email && $originSync->getLogs()) {
				$mail->To($error_email);
				$mail->Subject(self::plugin()->translate("summary_logs_in", "", [ $title ]));
				$msg = self::plugin()->translate("summary_logs");
				foreach ($originSync->getLogs() as $exception) {
					$msg .= "{$exception->getMessage()}\n";
					$msg .= self::plugin()->translate("summary_in", "", [ $exception->getFile() ]) . "\n";
				}
				$msg = rtrim($msg, "\n");

				$mail->Body($msg);
				$mail->Send();
			}
		}
	}


	/**
	 * @param IOriginSync $originSync
	 *
	 * @return string
	 */
	protected abstract function renderOneSync(IOriginSync $originSync): string;
}
