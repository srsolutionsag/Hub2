<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

/**
 * Handler
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
chdir(substr(__FILE__, 0, strpos(__FILE__, '/Customizing')));

require_once __DIR__ . '/vendor/autoload.php';

use srag\Plugins\Hub2\Shortlink\Handler;

$shortlink = new Handler($_GET['q']);
$shortlink->storeQuery();
$shortlink->tryILIASInit();
$shortlink->process();
