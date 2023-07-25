<?php

namespace srag\Plugins\Hub2\Jobs;

class CronNotifier implements Notifier
{
    public const NOTIFY_MODULO = 500;
    public const PING_MODULO = 500;
    private $ping_counter = 0;
    private $notify_counter = 0;
    /**
     * @var \ilLogger
     */
    protected $logger;

    public function __construct()
    {
        ini_set('zend.enable_gc', true);
        gc_enable();
        global $DIC;
        $this->logger = $DIC->logger()->root();
    }

    public function reset() : void
    {
        $this->ping_counter = 0;
        $this->notify_counter = 0;
    }

    private function pingCronJob() : void
    {
        if (php_sapi_name() === 'cli') {
            global $DIC;
            (new \ilCronManager($DIC->settings(), $DIC->logger()->root()))->ping(RunSync::CRON_JOB_ID);
        }
    }

    public function ping() : void
    {
        if ($this->ping_counter % self::PING_MODULO === 0) {
            $this->pingCronJob();
        }
        $this->ping_counter++;
    }

    public function notify(string $text) : void
    {
        $this->pingCronJob();
        $this->logger->write('HUB2: ' . $text);
    }

    public function notifySometimes(string $text) : void
    {
        if ($this->notify_counter % self::NOTIFY_MODULO === 0) {
            $this->notify($text . " ({$this->notify_counter})");
            if (gc_enabled()) {
                gc_collect_cycles();
            }
        }
        $this->notify_counter++;
    }
}
