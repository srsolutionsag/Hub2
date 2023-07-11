<?php

//namespace srag\Plugins\Hub2\UI\CustomView;

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\UI\CustomView\BaseCustomViewGUI;

/**
 * Class CustomViewGUI
 * @package srag\Plugins\Hub2\UI\CustomView
 * @author  Timon Amstutz
 */
class hub2CustomViewGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $class = null;
        try {
            $class_path = ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_PATH);
            if (!file_exists($class_path)) {
                throw new Exception("File " . $class_path . " doest not Exist");
            }
            require_once $class_path;

            $class_name = ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_CLASS);
            if (!class_exists($class_name)) {
                throw new Exception(
                    "Class " . $class_name . " not found. Note that namespaces need to be entered completely"
                );
            }

            $class = new $class_name($this);
            if (!($class instanceof BaseCustomViewGUI)) {
                throw new Exception("Class " . $class_name . " is not an instance of BaseCustomViewGUI");
            }
        } catch (Throwable $e) {
            ilUtil::sendInfo(
                self::plugin()->translate("admin_custom_view_class_not_found_1") . " '"
                . ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_PATH) . "' " . self::plugin()->translate(
                    "admin_custom_view_class_not_found_2"
                )
                . " Error: " . $e->getMessage()
            );
        }
        $class->executeCommand();
    }
}
