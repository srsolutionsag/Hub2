<?php
require_once __DIR__ . "/../vendor/autoload.php";

use SRAG\Plugins\Hub2\Config\ArConfig;
use SRAG\Plugins\Hub2\Config\HubConfig;
use SRAG\Plugins\Hub2\UI\ConfigFormGUI;

/**
 * Class hub2ConfigGUI
 *
 * @package
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class hub2ConfigGUI extends hub2MainGUI {

	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const CMD_SAVE_CONFIG = 'saveConfig';
	const CMD_CANCEL = 'cancel';


	/**
	 *
	 */
	protected function index() {
		$form = new ConfigFormGUI($this, new HubConfig());
		self::dic()->template()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function saveConfig() {
		$form = new ConfigFormGUI($this, new HubConfig());
		if ($form->checkInput()) {
			foreach ($form->getInputItemsRecursive() as $item) {
				/** @var ilFormPropertyGUI $item */
				ArConfig::setValueByKey($item->getPostVar(), $form->getInput($item->getPostVar()));
			}
			ilUtil::sendSuccess(self::translate('msg_successfully_saved'), true);
			self::dic()->ctrl()->redirect($this);
		}
		$form->setValuesByPost();
		self::dic()->template()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function initTabs() {
		self::dic()->tabs()->activateTab(self::TAB_PLUGIN_CONFIG);
	}
}
