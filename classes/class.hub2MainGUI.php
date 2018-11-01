<?php
require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Helper\DIC;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\UI\BaseCustomViewGUI;
/**
 * Class hub2MainGUI
 *
 * @package
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy hub2MainGUI: ilHub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigOriginsGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigGUI
 */
class hub2MainGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const TAB_PLUGIN_CONFIG = 'tab_plugin_config';
	const TAB_ORIGINS = 'tab_origins';
    const TAB_CUSTOM_VIEWS = 'admin_tab_custom_views';
	const CMD_INDEX = 'index';


	/**
	 * hub2MainGUI constructor.
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		$this->initTabs();
		$nextClass = self::dic()->ctrl()->getNextClass();

		if(self::dic()->ctrl()->getCmd() == self::TAB_CUSTOM_VIEWS){
		    $this->customViews();
		    return;
        }
		switch ($nextClass) {
			case strtolower(hub2ConfigGUI::class):
				self::dic()->ctrl()->forwardCommand(new hub2ConfigGUI());
				break;
			case strtolower(hub2ConfigOriginsGUI::class):
				self::dic()->ctrl()->forwardCommand(new hub2ConfigOriginsGUI());
				break;
			case strtolower(hub2DataGUI::class):
				break;
			default:
				$cmd = self::dic()->ctrl()->getCmd(self::CMD_INDEX);
				$this->{$cmd}();
		}
	}


	/**
	 *
	 */
	protected function index()/*: void*/ {
		self::dic()->ctrl()->redirectByClass(hub2ConfigGUI::class);
	}

    /**
     *
     */
	protected function customViews(){
        self::dic()->tabs()->activateTab(self::TAB_CUSTOM_VIEWS);

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


	/**
	 *
	 */
	protected function initTabs()/*: void*/ {
		self::dic()->tabs()->addTab(self::TAB_PLUGIN_CONFIG, self::plugin()->translate(self::TAB_PLUGIN_CONFIG), self::dic()->ctrl()
			->getLinkTargetByClass(hub2ConfigGUI::class));

		self::dic()->tabs()->addTab(self::TAB_ORIGINS, self::plugin()->translate(self::TAB_ORIGINS), self::dic()->ctrl()
			->getLinkTargetByClass(hub2ConfigOriginsGUI::class));

		if(ArConfig::isCustomViewsActive()){
            self::dic()->tabs()->addTab(self::TAB_CUSTOM_VIEWS, self::plugin()->translate(self::TAB_CUSTOM_VIEWS), self::dic()->ctrl()
                ->getLinkTargetByClass(self::class,self::TAB_CUSTOM_VIEWS));
        }

	}


	/**
	 *
	 */
	protected function cancel()/*: void*/ {
		$this->index();
	}
}
