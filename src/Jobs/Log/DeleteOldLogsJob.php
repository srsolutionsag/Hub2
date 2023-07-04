<?php

namespace srag\Plugins\Hub2\Jobs\Log;

use hub2LogsGUI;
use ilCronJob;
use ilCronJobResult;
use ilHub2Plugin;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Jobs\Result\ResultFactory;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class RunSync
 * @package srag\Plugins\Hub2\Jobs\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DeleteOldLogsJob extends ilCronJob
{
    use Hub2Trait;

    public const CRON_JOB_ID = ilHub2Plugin::PLUGIN_ID . "_delete_old_logs";
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var ilHub2Plugin
     */
    private $plugin;

    public function __construct()
    {
        $this->plugin = ilHub2Plugin::getInstance();
    }

    /**
     * Get id
     * @return string
     */
    public function getId() : string
    {
        return self::CRON_JOB_ID;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return ilHub2Plugin::PLUGIN_NAME . ": " . $this->plugin->txt("logs_cron");
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->plugin->txt("logs_cron_description");
    }

    /**
     * Is to be activated on "installation"
     * @return boolean
     */
    public function hasAutoActivation() : bool
    {
        return true;
    }

    /**
     * Can the schedule be configured?
     * @return boolean
     */
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }

    /**
     * Get schedule type
     * @return int
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
     * @return ilCronJobResult
     */
    public function run() : ilCronJobResult
    {
        $keep_old_logs_time = ArConfig::getField(ArConfig::KEY_KEEP_OLD_LOGS_TIME);

        $count = self::logs()->deleteOldLogs($keep_old_logs_time);

        return ResultFactory::ok(sprintf($this->plugin->txt("logs_deleted_status"), $count));
    }
}
