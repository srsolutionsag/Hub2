<?php

require_once(__DIR__ . '/class.ilHub2Plugin.php');
require_once(__DIR__ . '/class.hub2ConfigOriginsGUI.php');

use SRAG\Hub2\Config\ArConfig;
use SRAG\Hub2\Config\HubConfig;
use SRAG\Hub2\UI\ConfigFormGUI;

/**
 * Class ilHub2ConfigGUI
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilHub2ConfigGUI extends ilPluginConfigGUI {

	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilHub2Plugin
	 */
	protected $pl;

	public function __construct() {
		global $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->tpl = $DIC['tpl'];
		$this->pl = ilHub2Plugin::getInstance();
	}


	/**
	 * @param $cmd
	 */
	public function performCommand($cmd) {
		switch ($cmd) {
			case 'configure':
			case 'saveConfig':
				$this->$cmd();
				break;
		}
	}


	protected function configure() {
		$this->setTabs('config');
		$form = new ConfigFormGUI($this, new HubConfig());
		$this->tpl->setContent($form->getHTML());
	}

	protected function saveConfig() {
		$form = new ConfigFormGUI($this, new HubConfig());
		if ($form->checkInput()) {
			foreach ($form->getInputItemsRecursive() as $item) {
				/** @var $item \ilFormPropertyGUI */
				$config = ARConfig::getInstanceByKey($item->getPostVar());
				$config->setValue($form->getInput($item->getPostVar()));
				$config->save();
			}
			ilUtil::sendSuccess($this->pl->txt('msg_successfully_saved'), true);
			$this->ctrl->redirect($this, 'config');
		}
		$this->setTabs('configure');
		$form->setValuesByPost();
		$this->tpl->setContent($form->getHTML());
	}


	protected function setTabs($activeId) {
		global $DIC;
		$DIC->tabs()->addTab('config', 'Plugin Configuration', $this->ctrl->getLinkTarget($this, 'configure'));
		$DIC->tabs()->addTab('originConfig', 'Anbindungen', $this->ctrl->getLinkTargetByClass(['ilUIPluginRouterGUI', 'hub2ConfigOriginsGUI']));
		$DIC->tabs()->activateTab($activeId);
	}
}