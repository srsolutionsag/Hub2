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

use ilHub2Plugin;

/**
 * Class Ilias
 * @package srag\Plugins\Hub2\Access
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Ilias
{
    /**
     * @var string
     */
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    private static ?\srag\Plugins\Hub2\Access\Ilias $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Ilias constructor
     */
    private function __construct()
    {
    }
}
