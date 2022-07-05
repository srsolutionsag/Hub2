<?php

namespace srag\Plugins\Hub2\Jobs;


interface Notifier
{
    public function notify() : void;
}
