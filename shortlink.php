<?php
/**
 * Handler
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
chdir(substr(__FILE__, 0, strpos(__FILE__, '/Customizing')));
require_once('./Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php');

use SRAG\Plugins\Hub2\Shortlink\Handler;

$shortlink = new Handler($_GET['q']);
$shortlink->storeQuery();
$shortlink->tryILIASInit();
$shortlink->process();