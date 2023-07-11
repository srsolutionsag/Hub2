<?php

namespace srag\Plugins\Hub2\Jobs\Log;

use ilCronJob;
use ilCronJobResult;
use ilHub2Plugin;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Jobs\Result\ResultFactory;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class RunSync
 * @package srag\Plugins\Hub2\Jobs\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DeleteOldLogsJob extends ilCronJob
{
    public const CRON_JOB_ID = ilHub2Plugin::PLUGIN_ID . "_delete_old_logs";
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var ilHub2Plugin
     */
    private $plugin;
    /**
     * @var \srag\Plugins\Hub2\Log\IRepository
     */
    private $log_repo;

    public function __construct()
    {
        $this->plugin = ilHub2Plugin::getInstance();
        $this->log_repo = LogRepository::getInstance();
    }

    /**
     * Get id
     */
    public function getId() : string
    {
        return self::CRON_JOB_ID;
    }

    public function getTitle() : string
    {
        return ilHub2Plugin::PLUGIN_NAME . ": " . $this->plugin->txt("logs_cron");
    }

    public function getDescription() : string
    {
        return $this->plugin->txt("logs_cron_description");
    }

    /**
     * Is to be activated on "installation"
     */
    public function hasAutoActivation() : bool
    {
        return true;
    }

    /**
     * Can the schedule be configured?
     */
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }

    /**
     * Get schedule type
     */
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_DAILY;
    }

    /**
     * Get schedule value
     * @return int|array
     */
    public function getDefaultScheduleValue()
    {
        return null;
    }

    /**
     * Run job
     */
    public function run() : ilCronJobResult
    {
        $keep_old_logs_time = ArConfig::getField(ArConfig::KEY_KEEP_OLD_LOGS_TIME);

        $count = $this->log_repo->deleteOldLogs($keep_old_logs_time);

        return ResultFactory::ok(sprintf($this->plugin->txt("logs_deleted_status"), $count));
    }
}
