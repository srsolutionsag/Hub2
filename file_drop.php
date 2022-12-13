<?php

/**
 * FileDrop handler
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
chdir(substr(__FILE__, 0, strpos(__FILE__, '/Customizing')));

require_once __DIR__ . '/vendor/autoload.php';

use srag\Plugins\Hub2\FileDrop\Handler;

$shortlink = new Handler();
$shortlink->process();
