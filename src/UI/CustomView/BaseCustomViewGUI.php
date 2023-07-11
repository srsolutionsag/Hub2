<?php

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
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var hub2CustomViewGUI
     */
    protected $parent_gui;

    /**
     * BaseCustomViewGUI constructor
     */
    public function __construct(hub2CustomViewGUI $parent_gui)
    {
        $this->parent_gui = $parent_gui;
    }

    /**
     *
     */
    abstract public function executeCommand()/*: void*/
    ;
}
