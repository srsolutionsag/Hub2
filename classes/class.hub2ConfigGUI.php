<?php
require_once __DIR__ . "/../vendor/autoload.php";

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
	 * @return ConfigFormGUI
	 */
	protected function getConfigForm(): ConfigFormGUI {
		$form = new ConfigFormGUI($this);

		return $form;
	}


	/**
	 *
	 */
	protected function index()/*: void*/ {
		$form = $this->getConfigForm();

		self::plugin()->output($form);
	}


	/**
	 *
	 */
	protected function saveConfig()/*: void*/ {
		$form = $this->getConfigForm();

		if ($form->checkInput()) {
			$form->updateConfig();
			ilUtil::sendSuccess(self::plugin()->translate('msg_successfully_saved'), true);
			self::dic()->ctrl()->redirect($this);
		}
		$form->setValuesByPost();

		self::plugin()->output($form);
	}


	/**
	 *
	 */
	protected function initTabs()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_PLUGIN_CONFIG);
	}
}
