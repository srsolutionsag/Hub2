<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;

/**
 * Class FakeIliasObject
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class FakeIliasObject
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var string
     */
    protected $id;

    /**
     * FakeIliasObject constructor
     * @param string $id
     */
    public function __construct($id = "")
    {
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    abstract public function initId();
}
