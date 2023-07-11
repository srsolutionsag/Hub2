<?php

namespace srag\Plugins\Hub2\Shortlink;

use ilHub2Plugin;
use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class AbstractBaseLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractBaseLink implements IObjectLink
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var ARObject
     */
    protected $object;

    /**
     * AbstractBaseLink constructor
     */
    public function __construct(ARObject $object)
    {
        $this->object = $object;
    }

    /**
     * @inheritdoc
     */
    public function getNonExistingLink() : string
    {
        return "index.php";
    }
}
