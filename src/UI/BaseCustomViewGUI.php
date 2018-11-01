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

    public function __construct() {

    }

    abstract function index();
}