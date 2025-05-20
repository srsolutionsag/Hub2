<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

require_once __DIR__ . "/../vendor/autoload.php";
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
    //    use MockeryPHPUnitIntegration;
}
