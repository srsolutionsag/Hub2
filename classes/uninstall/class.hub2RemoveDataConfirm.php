<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Config\ArConfig;

/**
 * Class hub2RemoveDataConfirm
 *
 * @ilCtrl_isCalledBy hub2RemoveDataConfirm: ilUIPluginRouterGUI
 */
class hub2RemoveDataConfirm {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const CMD_CANCEL = "cancel";
	const CMD_CONFIRM_REMOVE_HUB2_DATA = "confirmRemoveHub2Data";
	const CMD_DEACTIVATE_HUB2 = "deactivateHub2";
	const CMD_SET_KEEP_HUB2_DATA = "setKeepHub2Data";
	const CMD_SET_REMOVE_HUB2_DATA = "setRemoveHub2Data";


	/**
	 * @param bool $plugin
	 */
	public static function saveParameterByClass(bool $plugin = true) {
		$ref_id = filter_input(INPUT_GET, "ref_id");
		self::dic()->ctrl()->setParameterByClass(ilObjComponentSettingsGUI::class, "ref_id", $ref_id);
		self::dic()->ctrl()->setParameterByClass(self::class, "ref_id", $ref_id);

		if ($plugin) {
			$ctype = filter_input(INPUT_GET, "ctype");
			self::dic()->ctrl()->setParameterByClass(ilObjComponentSettingsGUI::class, "ctype", $ctype);
			self::dic()->ctrl()->setParameterByClass(self::class, "ctype", $ctype);

			$cname = filter_input(INPUT_GET, "cname");
			self::dic()->ctrl()->setParameterByClass(ilObjComponentSettingsGUI::class, "cname", $cname);
			self::dic()->ctrl()->setParameterByClass(self::class, "cname", $cname);

			$slot_id = filter_input(INPUT_GET, "slot_id");
			self::dic()->ctrl()->setParameterByClass(ilObjComponentSettingsGUI::class, "slot_id", $slot_id);
			self::dic()->ctrl()->setParameterByClass(self::class, "slot_id", $slot_id);

			$plugin_id = filter_input(INPUT_GET, "plugin_id");
			self::dic()->ctrl()->setParameterByClass(ilObjComponentSettingsGUI::class, "plugin_id", $plugin_id);
			self::dic()->ctrl()->setParameterByClass(self::class, "plugin_id", $plugin_id);

			$pname = filter_input(INPUT_GET, "pname");
			self::dic()->ctrl()->setParameterByClass(ilObjComponentSettingsGUI::class, "pname", $pname);
			self::dic()->ctrl()->setParameterByClass(self::class, "pname", $pname);
		}
	}


	/**
	 *
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand() {
		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch ($next_class) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

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
	 * @param string $cmd
	 */
	protected function redirectToPlugins(string $cmd) {
		self::saveParameterByClass($cmd !== "listPlugins");

		self::dic()->ctrl()->redirectByClass([
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

		$confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

		$confirmation->setHeaderText(self::translate("uninstall_confirm_remove_hub2_data"));

		$confirmation->addItem("_", "_", self::translate("uninstall_hub2_data"));

		$confirmation->addButton(self::translate("uninstall_remove_hub2_data"), self::CMD_SET_REMOVE_HUB2_DATA);
		$confirmation->addButton(self::translate("uninstall_keep_hub2_data"), self::CMD_SET_KEEP_HUB2_DATA);
		$confirmation->addButton(self::translate("uninstall_deactivate_hub2"), self::CMD_DEACTIVATE_HUB2);
		$confirmation->setCancel(self::translate("button_cancel"), self::CMD_CANCEL);

		self::output($confirmation->getHTML());
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
		ArConfig::setUninstallRemoveHub2Data(false);

		ilUtil::sendInfo(self::translate("uninstall_msg_kept_hub2_data"), true);

		$this->redirectToPlugins("uninstallPlugin");
	}


	/**
	 *
	 */
	protected function setRemoveHub2Data() {
		ArConfig::setUninstallRemoveHub2Data(true);

		ilUtil::sendInfo(self::translate("uninstall_msg_removed_hub2_data"), true);

		$this->redirectToPlugins("uninstallPlugin");
	}
}
