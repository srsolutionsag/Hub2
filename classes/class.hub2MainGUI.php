<?php

use SRAG\Plugins\Hub2\Helper\DIC;

/**
 * Class hub2MainGUI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy hub2MainGUI: ilHub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigOriginsGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigGUI
 */
class hub2MainGUI {

	const TAB_PLUGIN_CONFIG = 'tab_plugin_config';
	const TAB_ORIGINS = 'tab_origins';
	const CMD_INDEX = 'index';
	/**
	 * @var \ilHub2Plugin
	 */
	protected $pl;
	use DIC;


	/**
	 * hub2MainGUI constructor.
	 */
	public function __construct() {
		$this->pl = ilHub2Plugin::getInstance();
	}


	public function executeCommand() {
		$this->initTabs();
		switch ($this->ctrl()->getNextClass()) {
			case strtolower(hub2ConfigGUI::class):
				$this->ctrl()->forwardCommand(new hub2ConfigGUI());
				break;
			case strtolower(hub2ConfigOriginsGUI::class):
				$this->ctrl()->forwardCommand(new hub2ConfigOriginsGUI());
				break;
			default:
				$cmd = $this->ctrl()->getCmd(self::CMD_INDEX);
				$this->{$cmd}();
		}
	}


	protected function index() {
		$this->ctrl()->redirectByClass(hub2ConfigGUI::class);
	}


	protected function initTabs() {
		$this->tabs()
		     ->addTab(self::TAB_PLUGIN_CONFIG, $this->pl->txt(self::TAB_PLUGIN_CONFIG), $this->ctrl()
		                                                                                     ->getLinkTargetByClass(hub2ConfigGUI::class));

		$this->tabs()->addTab(self::TAB_ORIGINS, $this->pl->txt(self::TAB_ORIGINS), $this->ctrl()
		                                                                                 ->getLinkTargetByClass(hub2ConfigOriginsGUI::class));
	}
}
