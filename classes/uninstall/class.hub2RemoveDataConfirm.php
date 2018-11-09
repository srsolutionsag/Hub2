<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\RemovePluginDataConfirm\AbstractRemovePluginDataConfirm;

/**
 * Class hub2RemoveDataConfirm
 *
 * @ilCtrl_isCalledBy hub2RemoveDataConfirm: ilUIPluginRouterGUI
 */
class hub2RemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
}
