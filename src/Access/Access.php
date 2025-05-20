<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Access;

/**
 * Class Access
 * @package srag\Plugins\Hub2\Access
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Access
{
    private static ?\srag\Plugins\Hub2\Access\Access $instance = null;

    public static function getInstance(): \srag\Plugins\Hub2\Access\Access
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Access constructor
     */
    private function __construct()
    {
    }
}
