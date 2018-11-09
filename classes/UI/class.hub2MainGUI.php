<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Config\ArConfig;

/**
 * Class hub2MainGUI
 *
 * @package
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy hub2MainGUI: ilHub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigOriginsGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2CustomViewGUI
 */
class hub2MainGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const TAB_PLUGIN_CONFIG = 'tab_plugin_config';
	const TAB_ORIGINS = 'tab_origins';
	const TAB_CUSTOM_VIEWS = 'admin_tab_custom_views';
	const CMD_INDEX = 'index';


	/**
	 * hub2MainGUI constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		$this->initTabs();
		$nextClass = self::dic()->ctrl()->getNextClass();

		switch ($nextClass) {
			case strtolower(hub2ConfigGUI::class):
				self::dic()->ctrl()->forwardCommand(new hub2ConfigGUI());
				break;
			case strtolower(hub2ConfigOriginsGUI::class):
				self::dic()->ctrl()->forwardCommand(new hub2ConfigOriginsGUI());
				break;
			case strtolower(hub2CustomViewGUI::class):
				self::dic()->tabs()->activateTab(self::TAB_CUSTOM_VIEWS);
				self::dic()->ctrl()->forwardCommand(new hub2CustomViewGUI());
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
	protected function initTabs()/*: void*/ {
		self::dic()->tabs()->addTab(self::TAB_PLUGIN_CONFIG, self::plugin()->translate(self::TAB_PLUGIN_CONFIG), self::dic()->ctrl()
			->getLinkTargetByClass(hub2ConfigGUI::class));

		self::dic()->tabs()->addTab(self::TAB_ORIGINS, self::plugin()->translate(self::TAB_ORIGINS), self::dic()->ctrl()
			->getLinkTargetByClass(hub2ConfigOriginsGUI::class));

		if (ArConfig::isCustomViewsActive()) {
			self::dic()->tabs()->addTab(self::TAB_CUSTOM_VIEWS, self::plugin()->translate(self::TAB_CUSTOM_VIEWS), self::dic()->ctrl()
				->getLinkTargetByClass(hub2CustomViewGUI::class));
		}
	}


	/**
	 *
	 */
	protected function cancel()/*: void*/ {
		$this->index();
	}
}
