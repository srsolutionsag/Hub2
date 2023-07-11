<?php

namespace srag\Plugins\Hub2\Access;

use ilHub2Plugin;

/**
 * Class Access
 * @package srag\Plugins\Hub2\Access
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Access
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var self
     */
    protected static $instance;

    public static function getInstance() : self
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
