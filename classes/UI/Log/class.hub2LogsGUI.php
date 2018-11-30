<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\Hub2\Log\LogsTableGUI;

/**
 * Class hub2LogsGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class hub2LogsGUI extends hub2MainGUI {

	const CMD_APPLY_FILTER = "applyFilter";
	const CMD_RESET_FILTER = "resetFilter";
	const LANG_MODULE_LOGS = "logs";


	/**
	 * @inheritdoc
	 */
	public function executeCommand()/*: void*/ {
		$this->initTabs();

		$cmd = self::dic()->ctrl()->getCmd(self::CMD_INDEX);

		switch ($cmd) {
			case self::CMD_INDEX:
			case self::CMD_APPLY_FILTER:
			case self::CMD_RESET_FILTER:
				$this->{$cmd}();
				break;

			default:
				break;
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function initTabs()/*: void*/ {
		self::dic()->tabs()->activateSubTab(hub2ConfigOriginsGUI::SUBTAB_LOGS);
	}


	/**
	 * @param string $cmd
	 *
	 * @return LogsTableGUI
	 */
	protected function getLogsTable($cmd = self::CMD_INDEX): LogsTableGUI {
		$table = new LogsTableGUI($this, $cmd);

		return $table;
	}


	/**
	 * @inheritdoc
	 */
	protected function index()/*: void*/ {
		$table = $this->getLogsTable();

		self::output()->output($table);
	}


	/**
	 *
	 */
	protected function applyFilter()/*: void*/ {
		$table = $this->getLogsTable(self::CMD_APPLY_FILTER);

		$table->writeFilterToSession();

		self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
	}


	/**
	 *
	 */
	protected function resetFilter()/*: void*/ {
		$table = $this->getLogsTable(self::CMD_RESET_FILTER);

		$table->resetFilter();

		$table->resetOffset();

		self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
	}
}
