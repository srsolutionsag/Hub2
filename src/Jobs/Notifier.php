<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Jobs;

interface Notifier
{
    public function ping(): void;

    public function reset(): void;

    public function notify(string $text): void;

    public function notifySometimes(string $text, string $namespace = 'default'): void;

    public function gc(): void;
}
