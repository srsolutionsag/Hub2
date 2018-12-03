<?php

namespace srag\Plugins\Hub2\Jobs;

use ilCronJob;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Jobs\Result\AbstractResult;
use srag\Plugins\Hub2\Jobs\Result\ResultFactory;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Sync\GlobalHook\GlobalHook;
use srag\Plugins\Hub2\Sync\OriginSyncFactory;
use srag\Plugins\Hub2\Sync\Summary\OriginSyncSummaryFactory;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use Throwable;

/**
 * Class RunSync
 *
 * @package srag\Plugins\Hub2\Jobs
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class RunSync extends ilCronJob {

	use DICTrait;
	use Hub2Trait;
	const CRON_JOB_ID = self::class;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @return string
	 */
	public function getId(): string {
		return self::CRON_JOB_ID;
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

			$global_hook = new GlobalHook();
			if (!$global_hook->beforeSync($OriginFactory->getAllActive())) {
				return ResultFactory::error("there was an error");
			}

			$summary = $OriginSyncSummaryFactory->cron();
			foreach ($OriginFactory->getAllActive() as $origin) {
				$originSyncFactory = new OriginSyncFactory($origin);
				$originSync = $originSyncFactory->instance();
				try {
					$originSync->execute();
				} catch (Throwable $e) {

				}
				self::logs()->originLog($originSync->getOrigin())->withMessage($summary->getSummaryOfOrigin($originSync))->store();

				$summary->addOriginSync($originSync);
			}
			$global_hook->afterSync($OriginFactory->getAllActive());

			$summary->sendNotifications();

			return ResultFactory::ok("everything's fine.");
		} catch (Throwable $e) {
			$global_hook->handleExceptions([ $e ]);

			return ResultFactory::error("there was an error");
		}
	}
}
