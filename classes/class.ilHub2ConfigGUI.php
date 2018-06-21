<?php

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Class ilHub2ConfigGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2ConfigGUI extends ilPluginConfigGUI {

	use \SRAG\Plugins\Hub2\Helper\DIC;


	/**
	 * @inheritDoc
	 */
	public function executeCommand() {
		parent::executeCommand();
		switch ($this->ctrl()->getNextClass()) {
			case strtolower(hub2MainGUI::class):
				$h = new hub2MainGUI();
				$this->ctrl()->forwardCommand($h);

				return;
		}
		$this->ctrl()->redirectByClass([ hub2MainGUI::class ]);
	}


	/**
	 * @param $cmd
	 */
	public function performCommand($cmd) {
		// noting to to here
	}
}
