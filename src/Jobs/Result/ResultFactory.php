<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Jobs\Result;

use ilCronJobResult;

/**
 * Class AbstractResult
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
final class ResultFactory
{
    /**
     * @return AbstractResult
     */
    public static function ok(string $message): ilCronJobResult
    {
        return new OK($message);
    }

    /**
     * @return AbstractResult
     */
    public static function error(string $message): ilCronJobResult
    {
        return new Error($message);
    }

    /**
     * ResultFactory constructor
     */
    private function __construct()
    {
    }
}
