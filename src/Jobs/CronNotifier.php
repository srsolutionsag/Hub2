<?php

namespace srag\Plugins\Hub2\Jobs;


class CronNotifier implements Notifier
{
    public function notify() : void
    {
        if (php_sapi_name() === 'cli') {
            \ilCronManager::ping(RunSync::CRON_JOB_ID);
        }
    }
}
