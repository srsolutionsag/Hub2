<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Jobs\Log;

use srag\Plugins\Hub2\Log\LogRepository;
use ilCronJob;
use ilCronJobResult;
use ilHub2Plugin;
use srag\Plugins\Hub2\Jobs\Result\ResultFactory;
use srag\Plugins\Hub2\Translator;
use srag\Plugins\Hub2\Log\LogDBRepository;
use srag\Plugins\Hub2\Config\ArConfig;

/**
 * Class RunSync
 *
 * @package srag\Plugins\Hub2\Jobs\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DeleteOldLogsJob extends ilCronJob
{
    public const CRON_JOB_ID = ilHub2Plugin::PLUGIN_ID . "_delete_old_logs";
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const KEEP_LATEST = 7;
    /**
     * @readonly
     */
    private Translator $translator;
    /**
     * @readonly
     */
    private LogRepository $log_repo;

    public function __construct()
    {
        global $DIC;
        $this->translator = ilHub2Plugin::getInstance();
        $this->log_repo = new LogDBRepository();
    }

    public function getId(): string
    {
        return self::CRON_JOB_ID;
    }

    public function getTitle(): string
    {
        return ilHub2Plugin::PLUGIN_NAME . ": " . $this->translator->txt("logs_cron");
    }

    public function getDescription(): string
    {
        return $this->translator->txt("logs_cron_description");
    }

    public function hasAutoActivation(): bool
    {
        return true;
    }

    public function hasFlexibleSchedule(): bool
    {
        return true;
    }

    public function getDefaultScheduleType(): int
    {
        return self::SCHEDULE_TYPE_DAILY;
    }

    public function getDefaultScheduleValue(): ?int
    {
        return null;
    }

    public function run(): ilCronJobResult
    {
        $current_seconds = time();
        $deleted_per_10_seconds = 0;

        $row_callback = function (array $row, int $removed_in_step) use (
            &$deleted_per_10_seconds,
            &$current_seconds
        ): void {
            global $DIC;
            $deleted_per_10_seconds += $removed_in_step;
            // count how much $removed_in_step stacks in 10 seconds
            if (time() - $current_seconds >= 10) {
                $DIC->logger()->root()->warning("Purging `$deleted_per_10_seconds` HUB2 logs in 10s");
                $current_seconds = time();
                $deleted_per_10_seconds = 0;
            }


            /** @var \ilCronManager $manager */
            $manager = $DIC['cron.manager'];
            $manager->ping(self::CRON_JOB_ID);
        };

        try {
            $keep_old_logs_time = (int) ArConfig::getField(ArConfig::KEY_KEEP_OLD_LOGS_TIME);
            $keep_old_logs_time = max($keep_old_logs_time, 7);

            $count = $this->log_repo->purge(self::KEEP_LATEST, $keep_old_logs_time, $row_callback);
        } catch (\Throwable $ex) {
            return ResultFactory::error($ex->getMessage());
        }

        return ResultFactory::ok(sprintf($this->translator->txt("logs_deleted_status"), $count));
    }
}
