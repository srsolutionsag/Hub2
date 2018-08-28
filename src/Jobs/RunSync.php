<?php

namespace SRAG\Plugins\Hub2\Jobs;

use Exception;
use ilCronJob;
use SRAG\Plugins\Hub2\Jobs\Result\AbstractResult;
use SRAG\Plugins\Hub2\Jobs\Result\ResultFactory;
use SRAG\Plugins\Hub2\Log\OriginLog;
use SRAG\Plugins\Hub2\Origin\OriginFactory;
use SRAG\Plugins\Hub2\Sync\OriginSyncFactory;
use SRAG\Plugins\Hub2\Sync\Summary\OriginSyncSummaryFactory;

/**
 * Class RunSync
 *
 * @package SRAG\Plugins\Hub2\Jobs
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class RunSync extends AbstractJob {

	/**
	 * @return string
	 */
	public function getId() {
		return get_class($this);
	}


	/**
	 * @return bool
	 */
	public function hasAutoActivation() {
		return true;
	}


	/**
	 * @return bool
	 */
	public function hasFlexibleSchedule() {
		return true;
	}


	/**
	 * @return int
	 */
	public function getDefaultScheduleType() {
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
	public function run() {
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

			return ResultFactory::ok("everything's fine.");
		} catch (Exception $e) {
			return ResultFactory::error("there was an error");
		}
	}
}
