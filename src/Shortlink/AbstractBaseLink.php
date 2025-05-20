<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
    protected ARObject $object;
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * AbstractBaseLink constructor
     */
    public function __construct(ARObject $object)
    {
        $this->object = $object;
    }

    public function getNonExistingLink(): string
    {
        return "index.php";
    }
}
