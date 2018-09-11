<?php

namespace srag\Plugins\Hub2\Jobs;

use Exception;
use ilCronJob;
use ilHub2Plugin;
use srag\Plugins\Hub2\Jobs\Result\AbstractResult;
use srag\Plugins\Hub2\Jobs\Result\ResultFactory;
use srag\Plugins\Hub2\Log\OriginLog;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Sync\OriginSyncFactory;
use srag\Plugins\Hub2\Sync\Summary\OriginSyncSummaryFactory;

/**
 * Class RunSync
 *
 * @package srag\Plugins\Hub2\Jobs
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class RunSync extends AbstractJob {

	/**
	 * @return string
	 */
	public function getId(): string {
		return get_class($this);
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return ilHub2Plugin::PLUGIN_NAME;
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return "";
	}


	/**
	 * @return bool
	 */
	public function hasAutoActivation(): bool {
		return true;
	}


	/**
	 * @return bool
	 */
	public function hasFlexibleSchedule(): bool {
		return true;
	}


	/**
	 * @return int
	 */
	public function getDefaultScheduleType(): int {
		return ilCronJob::SCHEDULE_TYPE_DAILY;
	}


	/**
	 * @return null
	 */
	public function getDefaultScheduleValue() {
		return 1;
	}


	/**
	 * @return AbstractResult
	 */
	public function run(): AbstractResult {
		try {
			$OriginSyncSummaryFactory = new OriginSyncSummaryFactory();

			$OriginFactory = new OriginFactory();

			$summary = $OriginSyncSummaryFactory->cron();
			foreach ($OriginFactory->getAllActive() as $origin) {
				$originSyncFactory = new OriginSyncFactory($origin);
				$originSync = $originSyncFactory->instance();
				try {
					$originSync->execute();
				} catch (Exception $e) {

				}
				$OriginLog = new OriginLog($originSync->getOrigin());
				$OriginLog->write($summary->getSummaryOfOrigin($originSync));

				$summary->addOriginSync($originSync);
			}

            $summary->sendNotifications();

			return ResultFactory::ok("everything's fine.");
		} catch (Exception $e) {
			return ResultFactory::error("there was an error");
		}
	}
}
