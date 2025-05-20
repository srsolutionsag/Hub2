<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\UI\CustomView;

use hub2CustomViewGUI;
use ilHub2Plugin;

/**
 * Class BaseCustomViewGUI
 * @package srag\Plugins\Hub2\UI\CustomView
 * @author  Timon Amstutz
 */
abstract class BaseCustomViewGUI
{
    protected \hub2CustomViewGUI $parent_gui;
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * BaseCustomViewGUI constructor
     */
    public function __construct(\hub2CustomViewGUI $parent_gui)
    {
        $this->parent_gui = $parent_gui;
    }

    /**
     *
     */
    abstract public function executeCommand()/*: void*/
    ;
}
