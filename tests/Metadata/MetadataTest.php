<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

require_once __DIR__ . "/../AbstractHub2Tests.php";

/**
 * Class MetadataTest
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 */
class MetadataTest extends AbstractHub2Tests
{
    protected function setUp(): void
    {
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_one_metadata_dto(): void
    {
    }
}
