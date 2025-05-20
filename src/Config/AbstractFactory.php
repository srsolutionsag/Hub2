<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Config;

/**
 * Class AbstractFactory
 *
 * @package srag\ActiveRecordConfig\Hub2\Config
 */
abstract class AbstractFactory
{
    /**
     * AbstractFactory constructor
     */
    protected function __construct()
    {
    }

    public function newInstance(): Config
    {
        return new Config();
    }
}
