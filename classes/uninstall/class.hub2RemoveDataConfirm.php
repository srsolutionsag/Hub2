<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use SRAG\Plugins\Hub2\Config\ArConfig;
use SRAG\Plugins\Hub2\Helper\DIC;

/**
 * Class hub2RemoveDataConfirm
 *
 * @ilCtrl_isCalledBy hub2RemoveDataConfirm: ilUIPluginRouterGUI
 */
class hub2RemoveDataConfirm {

	use DIC;
	const CMD_CANCEL = "cancel";
	const CMD_CONFIRM_REMOVE_HUB2_DATA = "confirmRemoveHub2Data";
	const CMD_DEACTIVATE_HUB2 = "deactivateHub2";
	const CMD_SET_KEEP_HUB2_DATA = "setKeepHub2Data";
	const CMD_SET_REMOVE_HUB2_DATA = "setRemoveHub2Data";


	/**
	 * @param bool $plugin
	 */
	public static function saveParameterByClass(bool $plugin = true) {
		global $DIC;
		$ilCtrl = $DIC->ctrl();

		$ref_id = filter_input(INPUT_GET, "ref_id");
		$ilCtrl->setParameterByClass(ilObjComponentSettingsGUI::class, "ref_id", $ref_id);
		$ilCtrl->setParameterByClass(self::class, "ref_id", $ref_id);

		if ($plugin) {
			$ctype = filter_input(INPUT_GET, "ctype");
			$ilCtrl->setParameterByClass(ilObjComponentSettingsGUI::class, "ctype", $ctype);
			$ilCtrl->setParameterByClass(self::class, "ctype", $ctype);

			$cname = filter_input(INPUT_GET, "cname");
			$ilCtrl->setParameterByClass(ilObjComponentSettingsGUI::class, "cname", $cname);
			$ilCtrl->setParameterByClass(self::class, "cname", $cname);

			$slot_id = filter_input(INPUT_GET, "slot_id");
			$ilCtrl->setParameterByClass(ilObjComponentSettingsGUI::class, "slot_id", $slot_id);
			$ilCtrl->setParameterByClass(self::class, "slot_id", $slot_id);

			$plugin_id = filter_input(INPUT_GET, "plugin_id");
			$ilCtrl->setParameterByClass(ilObjComponentSettingsGUI::class, "plugin_id", $plugin_id);
			$ilCtrl->setParameterByClass(self::class, "plugin_id", $plugin_id);

			$pname = filter_input(INPUT_GET, "pname");
			$ilCtrl->setParameterByClass(ilObjComponentSettingsGUI::class, "pname", $pname);
			$ilCtrl->setParameterByClass(self::class, "pname", $pname);
		}
	}


	/**
	 * @var ilHub2Plugin
	 */
	protected $pl;


	/**
	 *
	 */
	public function __construct() {
		$this->pl = ilHub2Plugin::getInstance();
	}


	/**
	 *
	 */
	public function executeCommand() {
		$next_class = $this->ctrl()->getNextClass($this);

		switch ($next_class) {
			default:
				$cmd = $this->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_CANCEL:
					case self::CMD_CONFIRM_REMOVE_HUB2_DATA:
					case self::CMD_DEACTIVATE_HUB2:
					case self::CMD_SET_KEEP_HUB2_DATA:
					case self::CMD_SET_REMOVE_HUB2_DATA:
						$this->{$cmd}();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 * @param string $html
	 */
	protected function show(string $html) {
		if ($this->ctrl()->isAsynch()) {
			echo $html;

			exit();
		} else {
			$this->tpl()->setContent($html);
			$this->tpl()->getStandardTemplate();
			$this->tpl()->show();
		}
	}


	/**
	 * @param string $cmd
	 */
	protected function redirectToPlugins(string $cmd) {
		self::saveParameterByClass($cmd !== "listPlugins");

		$this->ctrl()->redirectByClass([
			ilAdministrationGUI::class,
			ilObjComponentSettingsGUI::class
		], $cmd);
	}


	/**
	 *
	 */
	protected function cancel() {
		$this->redirectToPlugins("listPlugins");
	}


	/**
	 *
	 */
	protected function confirmRemoveHub2Data() {
		self::saveParameterByClass();

		$confirmation = new ilConfirmationGUI();

		$confirmation->setFormAction($this->ctrl()->getFormAction($this));

		$confirmation->setHeaderText($this->pl->txt("confirm_remove_hub2_data"));

		$confirmation->addItem("_", "_", $this->pl->txt("hub2_data"));

		$confirmation->addButton($this->pl->txt("remove_hub2_data"), self::CMD_SET_REMOVE_HUB2_DATA);
		$confirmation->addButton($this->pl->txt("keep_hub2_data"), self::CMD_SET_KEEP_HUB2_DATA);
		$confirmation->addButton($this->pl->txt("deactivate_hub2"), self::CMD_DEACTIVATE_HUB2);
		$confirmation->setCancel($this->pl->txt("button_cancel"), self::CMD_CANCEL);

		$this->show($confirmation->getHTML());
	}


	/**
	 *
	 */
	protected function deactivateHub2() {
		$this->redirectToPlugins("deactivatePlugin");
	}


	/**
	 *
	 */
	protected function setKeepHub2Data() {
		$uninstall_remove_hub2_data = ArConfig::getInstanceByKey(ilHub2Plugin::UNINSTALL_REMOVE_HUB2_DATA);
		$uninstall_remove_hub2_data->setValue(false);
		$uninstall_remove_hub2_data->store();

		ilUtil::sendInfo($this->pl->txt("msg_kept_hub2_data"), true);

		$this->redirectToPlugins("uninstallPlugin");
	}


	/**
	 *
	 */
	protected function setRemoveHub2Data() {
		$uninstall_remove_hub2_data = ArConfig::getInstanceByKey(ilHub2Plugin::UNINSTALL_REMOVE_HUB2_DATA);
		$uninstall_remove_hub2_data->setValue(true);
		$uninstall_remove_hub2_data->store();

		ilUtil::sendInfo($this->pl->txt("msg_removed_hub2_data"), true);

		$this->redirectToPlugins("uninstallPlugin");
	}
}
