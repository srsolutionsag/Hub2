<?php

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

    public function newInstance() : Config
    {
        return new Config();
    }
}
