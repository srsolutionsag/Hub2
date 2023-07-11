<?php

namespace srag\Plugins\Hub2\UI;

use ilHub2Plugin;
use srag\Plugins\Hub2\Origin\AROrigin;

/**
 * Class OriginFormFactory
 * @package srag\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFormFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    public function getFormClassNameByOrigin(AROrigin $origin) : string
    {
        $type = $origin->getObjectType();

        $ucfirst = ucfirst($type);

        return "srag\\Plugins\\Hub2\\UI\\" . $ucfirst . "\\" . $ucfirst . "OriginConfigFormGUI";
    }
}
