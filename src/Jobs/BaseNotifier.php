<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Jobs;

use ILIAS\Data\DataSize;

abstract class BaseNotifier implements Notifier
{
    public const NOTIFY_MODULO = 500;
    public const PING_MODULO = 500;
    protected int $ping_counter = 0;
    protected array $notify_counter = [];
    protected \ilLogger $logger;

    protected bool $force_gc = true;

    public function __construct()
    {
        global $DIC;

        if ($this->force_gc) {
            ini_set('zend.enable_gc', true);
            gc_enable();
        } else {
            ini_set('zend.enable_gc', false);
            gc_disable();
        }
        $this->logger = $DIC->logger()->root();
    }

    public function reset(): void
    {
        $this->ping_counter = 0;
        $this->notify_counter = [];
    }

    abstract protected function pingInternal(): void;

    public function ping(): void
    {
        if ($this->ping_counter % self::PING_MODULO === 0) {
            $this->pingInternal();
        }
        $this->ping_counter++;
    }

    public function notify(string $text): void
    {
        $this->logger->write('HUB2: ' . $text);
    }

    public function notifySometimes(string $text, string $namespace = 'default'): void
    {
        $this->notify_counter[$namespace] ??= 0;

        if ($this->notify_counter[$namespace] % self::NOTIFY_MODULO === 0) {
            $this->notify($text . " ({$this->notify_counter[$namespace]})");
            $this->gc();
        }
        $this->notify_counter[$namespace]++;
    }

    public function gc(): void
    {
        $current_memory = new DataSize(memory_get_usage(true), DataSize::Byte);
        $peak_memory = new DataSize(memory_get_peak_usage(true), DataSize::Byte);
        $this->notify("Current memory usage: " . $current_memory);
        $this->notify("Peak memory usage: " . $peak_memory);

        if (gc_enabled()) {
            $freed_cycles = gc_collect_cycles();
            $freed_caches = gc_mem_caches();
            $freed = new DataSize($freed_caches, DataSize::Byte);
            $this->notify("Reclaimed $freed bytes of memory");
        }
    }

}
