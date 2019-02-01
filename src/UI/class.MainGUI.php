<?php

namespace srag\Plugins\Hub2\UI;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\UI\Config\ConfigGUI;
use srag\Plugins\Hub2\UI\CustomView\CustomViewGUI;
use srag\Plugins\Hub2\UI\Data\DataGUI;
use srag\Plugins\Hub2\UI\Log\LogsGUI;
use srag\Plugins\Hub2\UI\OriginConfig\ConfigOriginsGUI;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class MainGUI
 *
 * @package           srag\Plugins\Hub2\UI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy srag\Plugins\Hub2\UI\MainGUI: ilHub2ConfigGUI
 */
class MainGUI {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const TAB_PLUGIN_CONFIG = 'tab_plugin_config';
	const TAB_ORIGINS = 'tab_origins';
	const TAB_CUSTOM_VIEWS = 'admin_tab_custom_views';
	const CMD_INDEX = 'index';


	/**
	 * MainGUI constructor
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
			case strtolower(ConfigGUI::class):
				self::dic()->ctrl()->forwardCommand(new ConfigGUI());
				break;
			case strtolower(ConfigOriginsGUI::class):
				self::dic()->ctrl()->forwardCommand(new ConfigOriginsGUI());
				break;
			case strtolower(CustomViewGUI::class):
				self::dic()->tabs()->activateTab(self::TAB_CUSTOM_VIEWS);
				self::dic()->ctrl()->forwardCommand(new CustomViewGUI());
				break;
			case strtolower(DataGUI::class):
			case strtolower(LogsGUI::class):
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
		self::dic()->ctrl()->redirectByClass(ConfigGUI::class);
	}


	/**
	 *
	 */
	protected function initTabs()/*: void*/ {
		self::dic()->tabs()->addTab(self::TAB_PLUGIN_CONFIG, self::plugin()->translate(self::TAB_PLUGIN_CONFIG), self::dic()->ctrl()
			->getLinkTargetByClass(ConfigGUI::class));

		self::dic()->tabs()->addTab(self::TAB_ORIGINS, self::plugin()->translate(self::TAB_ORIGINS), self::dic()->ctrl()
			->getLinkTargetByClass(ConfigOriginsGUI::class));

		if (ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_ACTIVE)) {
			self::dic()->tabs()->addTab(self::TAB_CUSTOM_VIEWS, self::plugin()->translate(self::TAB_CUSTOM_VIEWS), self::dic()->ctrl()
				->getLinkTargetByClass(CustomViewGUI::class));
		}
	}


	/**
	 *
	 */
	protected function cancel()/*: void*/ {
		$this->index();
	}
}
