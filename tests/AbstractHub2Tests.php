<?php

// Autoload Hub2
require_once(dirname(__DIR__) . '/vendor/autoload.php');

/**
 * Base class for all unit tests of Hub2
 *
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 */
abstract class AbstractHub2Tests extends \PHPUnit\Framework\TestCase {

	use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
}