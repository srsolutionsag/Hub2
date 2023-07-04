<?php
/**
 * ErrorHandler
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
chdir(substr(__FILE__, 0, strpos(__FILE__, '/Customizing')));
/** @noRector */
include_once( "./include/inc.header.php");
header("Location: /error.php");
