<?php

namespace srag\Plugins\Hub2\UI;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class BaseCustomViewGUI
 *
 * @package srag\Plugins\Hub2\UI
 * @author  Timon Amstutz
 */
abstract class BaseCustomViewGUI {
    use DICTrait;
    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @var \hub2CustomViewGUI
     */
    protected $parent_gui;

    public function __construct(\hub2CustomViewGUI $parent_gui) {
        $this->parent_gui = $parent_gui;
    }

    abstract function executeCommand();
}