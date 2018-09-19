<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use SRAG\Plugins\Hub2\Config\ArConfig;
use srag\RemovePluginDataConfirm\AbstractRemovePluginDataConfirm;

/**
 * Class hub2RemoveDataConfirm
 *
 * @ilCtrl_isCalledBy hub2RemoveDataConfirm: ilUIPluginRouterGUI
 */
class hub2RemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritdoc
	 */
	public function getUninstallRemovesData() {
		return ArConfig::getUninstallRemovesData();
	}


	/**
	 * @inheritdoc
	 */
	public function setUninstallRemovesData($uninstall_removes_data) {
		ArConfig::setUninstallRemovesData($uninstall_removes_data);
	}


	/**
	 * @inheritdoc
	 */
	public function removeUninstallRemovesData() {
		ArConfig::removeUninstallRemovesData();
	}
}
