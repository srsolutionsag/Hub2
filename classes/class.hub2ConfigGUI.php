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

	const CMD_SAVE_CONFIG = 'saveConfig';
	const CMD_CANCEL = 'cancel';


	/**
	 *
	 */
	protected function index() {
		$form = new ConfigFormGUI($this, new HubConfig());
		$this->tpl()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function saveConfig() {
		$form = new ConfigFormGUI($this, new HubConfig());
		if ($form->checkInput()) {
			foreach ($form->getInputItemsRecursive() as $item) {
				/** @var ilFormPropertyGUI $item */
				$config = ARConfig::getInstanceByKey($item->getPostVar());
				$config->setValue($form->getInput($item->getPostVar()));
				$config->save();
			}
			ilUtil::sendSuccess($this->pl->txt('msg_successfully_saved'), true);
			$this->ctrl()->redirect($this);
		}
		$form->setValuesByPost();
		$this->tpl()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function initTabs() {
		$this->tabs()->activateTab(self::TAB_PLUGIN_CONFIG);
	}
}
