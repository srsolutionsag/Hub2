<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Jobs;

class CronNotifier extends BaseNotifier
{
    protected function pingInternal(): void
    {
        if (PHP_SAPI === 'cli') {
            global $DIC;
            if (!isset($DIC['cron.repository'])) {
                return;
            }

            $DIC['cron.manager']->ping(RunSync::CRON_JOB_ID);
        }
    }
}
