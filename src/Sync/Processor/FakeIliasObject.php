<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class FakeIliasObject
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class FakeIliasObject
{

    use DICTrait;
    use Hub2Trait;

    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
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

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public abstract function initId();
}
