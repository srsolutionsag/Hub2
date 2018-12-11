<?php

namespace srag\Plugins\Hub2\Log;

use hub2LogsGUI;
use ilDateTime;
use ilHub2Plugin;
use ilSelectInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\Hub2\DateDurationInputGUI\DateDurationInputGUI;
use srag\CustomInputGUIs\Hub2\NumberInputGUI\NumberInputGUI;
use srag\CustomInputGUIs\Hub2\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\Hub2\TableGUI\TableGUI;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use stdClass;

/**
 * Class LogsTableGUI
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LogsTableGUI extends TableGUI {

	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const LANG_MODULE = hub2LogsGUI::LANG_MODULE_LOGS;


	/**
	 * @inheritdoc
	 */
	protected function getColumnValue(/*string*/
		$column, /*array*/
		$row, /*bool*/
		$raw_export = false): string {
		switch ($column) {
			case "log_type":
				$column = $this->txt("log_type_" . $row[$column]);
				break;

			case "level":
				$column = $this->txt("level_" . $row[$column]);
				break;

			case "origin_object_type":
				$column = self::plugin()->translate("origin_object_type_" . $row[$column]);
				break;

			case "additional_data":
				$column = $row[$column];
				if (!is_object($column)) {
					$column = json_decode($column);
				}
				if (!is_object($column)) {
					$column = new stdClass();
				}
				$column = get_object_vars($column);

				$column = implode("<br>", array_map(function (string $key, $value): string {
					return "$key: $value";
				}, array_keys($column), $column));

				if (empty($column)) {
					$column = self::plugin()->translate("no_additional_data", hub2LogsGUI::LANG_MODULE_LOGS);
				}
				break;

			default:
				$column = $row[$column];
				break;
		}

		return strval($column);
	}


	/**
	 * @inheritdoc
	 */
	public function getSelectableColumns2(): array {
		$columns = [
			"log_type" => "log_type",
			"title" => "title",
			"message" => "message",
			"date" => "date",
			"level" => "level",
			"origin_id" => "origin_id",
			"origin_object_type" => "origin_object_type",
			"additional_data" => "additional_data"
		];

		$columns = array_map(function (string $key): array {
			return [
				"id" => $key,
				"default" => ($key !== "additional_data"),
				"sort" => ($key !== "additional_data")
			];
		}, $columns);

		return $columns;
	}


	/**
	 * @inheritdoc
	 */
	protected function initColumns()/*: void*/ {
		parent::initColumns();

		$this->setDefaultOrderField("date");
		$this->setDefaultOrderDirection("desc");

		$this->setExternalSorting(true);
		$this->setExternalSegmentation(true);
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$filter = $this->getFilterValues();

		$log_type = $filter["log_type"];
		if (!empty($log_type)) {
			$log_type = intval($log_type);
		} else {
			$log_type = NULL;
		}
		$title = $filter["title"];
		$message = $filter["message"];
		$date_start = $filter["date"]["start"];
		if (!empty($date_start)) {
			$date_start = new ilDateTime(intval($date_start), IL_CAL_UNIX);
		} else {
			$date_start = NULL;
		}
		$date_end = $filter["date"]["end"];
		if (!empty($date_end)) {
			$date_end = new ilDateTime(intval($date_end), IL_CAL_UNIX);
		} else {
			$date_end = NULL;
		}
		$level = $filter["level"];
		if (!empty($level)) {
			$level = intval($level);
		} else {
			$level = NULL;
		}
		$origin_id = $filter["origin_id"];
		if (!empty($origin_id)) {
			$origin_id = intval($origin_id);
		} else {
			$origin_id = NULL;
		}
		$origin_object_type = $filter["origin_object_type"];
        $additional_data = $filter["additional_data"];

		// Fix stupid ilTable2GUI !!! ...
		$this->determineOffsetAndOrder();

		$logs = self::logs()
			->getLogs($this->getOrderField(), $this->getOrderDirection(), intval($this->getOffset()), intval($this->getLimit()), $log_type, $title, $message, $date_start, $date_end, $level, $origin_id, $origin_object_type,$additional_data);

		$this->setData($logs);

		$this->setMaxCount(count(self::logs()->getLogs()));
	}


	/**
	 * @inheritdoc
	 */
	protected function initFilterFields()/*: void*/ {
		self::dic()->language()->loadLanguageModule("form");

		$this->filter_fields = [
			"log_type" => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_OPTIONS => [
						"" => "",
					] + array_map(function (int $log_type): string {
						return $this->txt("log_type_" . $log_type);
					}, Log::$log_types)
			],
			"title" => [
				PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
			],
			"message" => [
				PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
			],
			"date" => [
				PropertyFormGUI::PROPERTY_CLASS => DateDurationInputGUI::class,
				"setShowTime" => true
			],
			"level" => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_OPTIONS => [
						"" => "",
					] + array_map(function (int $level): string {
						return $this->txt("level_" . $level);
					}, Log::$levels)
			],
            /**
             * Those two fields will not work. This (along with other things, such as ordering)
             * completely breaks due to the design of the data layer, having logs in 2 tables.
             *
             * @Todo: Keep logs in one table only to avoid such issues.

			"origin_id" => [
				PropertyFormGUI::PROPERTY_CLASS => NumberInputGUI::class,
				"setMinValue" => 0
			],
			"origin_object_type" => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_OPTIONS => [
						"" => "",
					] + array_map(function (string $origin_object_type): string {
						return self::plugin()->translate("origin_object_type_" . $origin_object_type);
					}, AROrigin::$object_types)
			],
             *              */
            "additional_data" => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
            ],
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("hub2_logs");
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {
		$this->setTitle("logs");
	}
}
