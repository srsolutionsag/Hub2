<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Base class for all unit tests of Hub2
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 */
abstract class AbstractHub2Tests extends TestCase
{
    use MockeryPHPUnitIntegration;
}
