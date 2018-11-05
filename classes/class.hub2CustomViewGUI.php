<?php
require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\Hub2\UI\BaseCustomViewGUI;
use srag\Plugins\Hub2\Config\ArConfig;

/**
 * Class hub2CustomViewGUI
 *
 * @package
 * @author  Timon Amstutz
 *
 */
class hub2CustomViewGUI {

	use DICTrait;

	public function executeCommand()/*: void*/ {

        try{
            if(!file_exists(ArConfig::getCustomViewsPath())){
                throw new Exception("File ".ArConfig::getCustomViewsPath()." doest not Exist");
            }
            include_once (ArConfig::getCustomViewsPath());
            $class_name = ArConfig::getCustomViewsClass();
            if(!class_exists($class_name)){
                throw new Exception("Class ".$class_name." not found. Note that namespaces need to be entered completely");

            }
            $class = new $class_name();
            if(!($class instanceof BaseCustomViewGUI)){
                throw new Exception("Class ".$class_name." is not an instance of BaseCustomViewGUI");
            }
            $class->index();
        }catch(\Exception $e){
            ilUtil::sendInfo(self::plugin()->translate("admin_custom_view_class_not_found_1")." '"
                .ArConfig::getCustomViewsPath()."' ".self::plugin()->translate("admin_custom_view_class_not_found_2"). " Error: " .$e->getMessage());
        }
	}
}
