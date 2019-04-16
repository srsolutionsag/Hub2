<?php

//namespace srag\Plugins\Hub2\UI\Log;

use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use srag\Plugins\Hub2\UI\Log\LogsTableGUI;

/**
 * Class LogsGUI
 *
 * @package srag\Plugins\Hub2\UI\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class hub2LogsGUI extends hub2MainGUI {

	const CMD_APPLY_FILTER = "applyFilter";
	const CMD_RESET_FILTER = "resetFilter";
	const CMD_SHOW_LOGS_OF_EXT_ID = "showLogsOfExtID";
	const SUBTAB_LOGS = "subtab_logs";
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
			case self::CMD_SHOW_LOGS_OF_EXT_ID:
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
		self::dic()->tabs()->activateSubTab(self::SUBTAB_LOGS);
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

		$table->resetOffset();

		//self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
		$this->index(); // Fix reset offset
	}


	/**
	 *
	 */
	protected function resetFilter()/*: void*/ {
		$table = $this->getLogsTable(self::CMD_RESET_FILTER);

		$table->resetFilter();

		$table->resetOffset();

		//self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
		$this->index(); // Fix reset offset
	}


	/**
	 *
	 */
	protected function showLogsOfExtID()/*: void*/ {
		$origin_id = intval(filter_input(INPUT_GET, DataTableGUI::F_ORIGIN_ID));
		$ext_id = filter_input(INPUT_GET, DataTableGUI::F_EXT_ID);

		$table = $this->getLogsTable(self::CMD_RESET_FILTER);
		$table->resetFilter();
		$table->resetOffset();

		$_POST["origin_id"] = $origin_id;
		$_POST["object_ext_id"] = $ext_id;
		$this->applyFilter();
	}
}
