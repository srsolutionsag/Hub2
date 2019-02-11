<?php

namespace srag\Plugins\Hub2\Jobs;

use ilCronJob;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Exception\AbortOriginSyncException;
use srag\Plugins\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use srag\Plugins\Hub2\Exception\AbortSyncException;
use srag\Plugins\Hub2\Jobs\Result\AbstractResult;
use srag\Plugins\Hub2\Jobs\Result\ResultFactory;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Sync\GlobalHook\GlobalHook;
use srag\Plugins\Hub2\Sync\OriginSyncFactory;
use srag\Plugins\Hub2\Sync\Summary\IOriginSyncSummary;
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
	const CRON_JOB_ID = ilHub2Plugin::PLUGIN_ID;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IOrigin[]
	 */
	protected $origins;
	/**
	 * @var IOriginSyncSummary
	 */
	protected $summary = NULL;
	/**
	 * @var IOriginSyncSummary
	 */
	protected $force_update;


	/**
	 * RunSync constructor
	 *
	 * @param IOrigin[]               $origins
	 * @param IOriginSyncSummary|null $summary
	 * @param bool                    $force_update
	 */
	public function __construct(array $origins = [],/*?*/
		IOriginSyncSummary $summary = NULL, bool $force_update = false) {
		$this->origins = $origins;
		$this->summary = $summary;
		$this->force_update = $force_update;
	}


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
			$skip_object_type = '';

			$global_hook = GlobalHook::getInstance();

			if (empty($this->origins)) {
				$this->origins = (new OriginFactory())->getAllActive();
			}

			if (empty($this->summary)) {
				$this->summary = (new OriginSyncSummaryFactory())->mail();
			}

			if (!$global_hook->beforeSync($this->origins)) {
				return ResultFactory::error("there was an error");
			}

			foreach ($this->origins as $origin) {
				if ($origin->getObjectType() == $skip_object_type) {
					continue;
				}

				if ($this->force_update) {
					$origin->forceUpdate();
				}

				$originSyncFactory = new OriginSyncFactory($origin);

				$originSync = $originSyncFactory->instance();

				try {
					$originSyncFactory->initImplementation($originSync);

					$originSync->execute();
				} catch (AbortSyncException $e) {
					throw $e;
				} catch (AbortOriginSyncException $ex) {
					break;
				} catch (AbortOriginSyncOfCurrentTypeException $e) {
					$skip_object_type = $origin->getObjectType();
					continue;
				} catch (Throwable $e) {
					self::logs()->exceptionLog($e, $origin)->store();
				}

				$this->summary->addOriginSync($originSync);
			}

			$this->summary->sendEmail();

			if (!$global_hook->afterSync($this->origins)) {
				return ResultFactory::error("there was an error");
			}

			return ResultFactory::ok("everything's fine.");
		} catch (Throwable $e) {
			return ResultFactory::error("there was an error");
		}
	}
}
