<?php
/**
 * Shortlink
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
error_reporting(E_ALL);
ini_set('display_error', 'stdout');
chdir(substr(__FILE__, 0, strpos(__FILE__, '/Customizing')));
require_once('./Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php');

use SRAG\Plugins\Hub2\Shortlink\Shortlink;

$shortlink = new Shortlink($_GET['q']);
$shortlink->doRedirect();