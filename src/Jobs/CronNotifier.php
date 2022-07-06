<?php

namespace srag\Plugins\Hub2\Jobs;


class CronNotifier implements Notifier
{
    const MODULO = 500;
    private $ping_counter = 0;
    private $notify_counter = 0;
    
    public function __construct()
    {
        ini_set('zend.enable_gc', true);
        gc_enable();
    }
    
    private function pingCronJob() : void
    {
        if (php_sapi_name() === 'cli') {
            \ilCronManager::ping(RunSync::CRON_JOB_ID);
        }
    }
    
    public function ping() : void
    {
        if ($this->ping_counter === 100) {
            $this->pingCronJob();
            $this->ping_counter = 0;
        } else {
            $this->ping_counter++;
        }
    }
    
    public function notify(string $text) : void
    {
        $this->pingCronJob();
        global $DIC;
        $DIC->logger()->root()->write('HUB2: ' . $text);
    }
    
    public function notifySometimes(string $text) : void
    {
        if ($this->notify_counter % self::MODULO === 0) {
            $this->notify($text . " ({$this->notify_counter})");
            if (gc_enabled()) {
                $collected = gc_collect_cycles();
                $this->notify("GC collected $collected cycles");
            }
        }
        $this->notify_counter++;
    }
    
}
