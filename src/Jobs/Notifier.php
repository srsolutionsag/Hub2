<?php

namespace srag\Plugins\Hub2\Jobs;

interface Notifier
{
    public function ping(): void;

    public function reset(): void;

    public function notify(string $text): void;

    public function notifySometimes(string $text): void;
}
