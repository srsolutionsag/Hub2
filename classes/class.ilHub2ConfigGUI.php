<?php

require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');

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
		$this->ctrl = $DIC['ilCtrl'];
		$this->tpl = $DIC['tpl'];
		$this->pl = ilHub2Plugin::getInstance();
	}


	/**
	 * @param $cmd
	 */
	public function performCommand($cmd) {
		switch ($cmd) {
			case 'configure':
			case 'save':
				$this->$cmd();
				break;
		}
	}


	protected function configure() {
		$form = new hubConfigFormGUI($this);
		$form->fillForm();
		$this->tpl->setContent($form->getHTML());
	}


	protected function save() {
		$form = new hubConfigFormGUI($this);
		$form->setValuesByPost();
		if ($form->saveObject()) {
			$this->ctrl->redirect($this, 'configure');
		}
		$this->tpl->setContent($form->getHTML());
	}
}