<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\UI\MainGUI;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class ilHub2ConfigGUI
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2ConfigGUI extends ilPluginConfigGUI {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @param string $cmd
	 */
	public function performCommand($cmd) {
		switch (self::dic()->ctrl()->getNextClass()) {
			case strtolower(MainGUI::class):
				$h = new MainGUI();
				self::dic()->ctrl()->forwardCommand($h);
				break;

			default:
				self::dic()->ctrl()->redirectByClass([ MainGUI::class ]);
				break;
		}
	}
}
