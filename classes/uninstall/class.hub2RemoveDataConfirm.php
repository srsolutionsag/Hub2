<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\Hub2\Utils\Hub2Trait;
use srag\RemovePluginDataConfirm\Hub2\AbstractRemovePluginDataConfirm;

/**
 * Class hub2RemoveDataConfirm
 *
 * @ilCtrl_isCalledBy hub2RemoveDataConfirm: ilUIPluginRouterGUI
 */
class hub2RemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
}
