<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;

/**
 * Class FakeIliasObject
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class FakeIliasObject
{
    /**
     * @var string
     */
    protected $id = "";
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * FakeIliasObject constructor
     * @param string $id
     */
    public function __construct($id = "")
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    abstract public function initId();
}
