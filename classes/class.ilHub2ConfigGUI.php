<?php

use srag\DIC\DICTrait;

require_once __DIR__ . "/../vendor/autoload.php";

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
	 * @inheritDoc
	 */
	public function executeCommand() {
		parent::executeCommand();
		switch (self::dic()->ctrl()->getNextClass()) {
			case strtolower(hub2MainGUI::class):
				$h = new hub2MainGUI();
				self::dic()->ctrl()->forwardCommand($h);

				return;
		}
		self::dic()->ctrl()->redirectByClass([ hub2MainGUI::class ]);
	}


	/**
	 * @param string $cmd
	 */
	public function performCommand($cmd) {
		// noting to to here
	}
}
