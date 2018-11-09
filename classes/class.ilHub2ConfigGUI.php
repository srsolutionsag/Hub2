<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;

/**
 * Class ilHub2ConfigGUI
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2ConfigGUI extends ilPluginConfigGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @param string $cmd
	 */
	public function performCommand($cmd) {
		switch (self::dic()->ctrl()->getNextClass()) {
			case strtolower(hub2MainGUI::class):
				$h = new hub2MainGUI();
				self::dic()->ctrl()->forwardCommand($h);
				break;

			default:
				self::dic()->ctrl()->redirectByClass([ hub2MainGUI::class ]);
				break;
		}
	}
}
