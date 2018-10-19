<?php
/**
 * Handler
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
chdir(substr(__FILE__, 0, strpos(__FILE__, '/Customizing')));

require_once __DIR__ . '/vendor/autoload.php';

use srag\Plugins\Hub2\Shortlink\Handler;

$shortlink = new Handler($_COOKIE['xhub_query']);
$shortlink->tryILIASInitPublic();
$shortlink->process();
