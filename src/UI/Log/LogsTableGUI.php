<?php

namespace srag\Plugins\Hub2\UI\Log;

use hub2LogsGUI;
use ilDateTime;
use ilHub2Plugin;
use ilSelectInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\Hub2\DateDurationInputGUI\DateDurationInputGUI;
use srag\CustomInputGUIs\Hub2\NumberInputGUI\NumberInputGUI;
use srag\CustomInputGUIs\Hub2\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\Hub2\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\Hub2\TableGUI\TableGUI;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use stdClass;

/**
 * Class LogsTableGUI
 * @package srag\Plugins\Hub2\UI\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LogsTableGUI extends TableGUI
{

    use Hub2Trait;

    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    const LANG_MODULE = hub2LogsGUI::LANG_MODULE_LOGS;

    /**
     * @inheritdoc
     * @param ILog $row
     */
    protected function getColumnValue(/*string*/
        $column, /*ILog*/
        $row, /*bool*/
        $raw_export = false
    ) : string {
        $value = Items::getter($row, $column);

        switch ($column) {
            case "level":
                $value = $this->txt("level_" . $value);
                break;

            case "origin_object_type":
                $value = self::plugin()->translate("origin_object_type_" . $value);
                break;

            case "status":
                $value = $value ? self::plugin()->translate("data_table_status_" . ARObject::$available_status[$value]) : '';
                break;

            case "additional_data":
                if (!is_object($value)) {
                    $value = json_decode($value);
                }
                if (!is_object($value)) {
                    $value = new stdClass();
                }
                $value = get_object_vars($value);

                $value = implode(
                    "<br>", array_map(
                        function (string $key, $value) : string {
                            return "$key: $value";
                        }, array_keys($value), $value
                    )
                );

                if (empty($value)) {
                    $value = self::plugin()->translate("no_additional_data", hub2LogsGUI::LANG_MODULE_LOGS);
                }
                break;

            default:
                break;
        }

        return strval($value);
    }

    /**
     * @inheritdoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "date" => "date",
            "origin_id" => "origin_id",
            "origin_object_type" => "origin_object_type",
            "status" => "status",
            "object_ext_id" => "object_ext_id",
            "object_ilias_id" => "object_ilias_id",
            "title" => "title",
            "message" => "message",
            "level" => "level",
            "additional_data" => "additional_data",
        ];

        $columns = array_map(
            function (string $key) : array {
                return [
                    "id" => $key,
                    "default" => true,
                    "sort" => ($key !== "additional_data"),
                ];
            }, $columns
        );

        return $columns;
    }

    /**
     * @inheritdoc
     */
    protected function initColumns()/*: void*/
    {
        parent::initColumns();
    }

    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {

    }

    /**
     * @inheritdoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setDefaultOrderField("date");
        $this->setDefaultOrderDirection("desc");

        // Fix stupid ilTable2GUI !!! ...
        $this->determineLimit();
        $this->determineOffsetAndOrder();

        $filter = $this->getFilterValues();

        $title = $filter["title"];
        $message = $filter["message"];
        $date_start = $filter["date"]["start"];
        if (!empty($date_start)) {
            $date_start = new ilDateTime(intval($date_start), IL_CAL_UNIX);
        } else {
            $date_start = null;
        }
        $date_end = $filter["date"]["end"];
        if (!empty($date_end)) {
            $date_end = new ilDateTime(intval($date_end), IL_CAL_UNIX);
        } else {
            $date_end = null;
        }
        $level = $filter["level"];
        if (!empty($level)) {
            $level = intval($level);
        } else {
            $level = null;
        }
        $origin_id = $filter["origin_id"];
        if (!empty($origin_id)) {
            $origin_id = intval($origin_id);
        } else {
            $origin_id = null;
        }
        $origin_object_type = $filter["origin_object_type"];
        $object_ext_id = $filter["object_ext_id"];
        $object_ilias_id = $filter["object_ilias_id"];
        if (!empty($object_ilias_id)) {
            $object_ilias_id = intval($object_ilias_id);
        } else {
            $object_ilias_id = null;
        }
        $additional_data = $filter["additional_data"];
        $status = intval($filter["status"]);

        $this->setData(
            self::logs()
                ->getLogs(
                    $this->getOrderField(), $this->getOrderDirection(), intval($this->getOffset()),
                    intval($this->getLimit()), $title, $message, $date_start, $date_end, $level, $origin_id,
                    $origin_object_type, $object_ext_id, $object_ilias_id, $additional_data, $status
                )
        );

        $this->setMaxCount(
            self::logs()
                ->getLogsCount($title, $message, $date_start, $date_end, $level, $origin_id, $origin_object_type,
                    $object_ext_id, $object_ilias_id, $additional_data, $status)
        );
    }

    /**
     * @inheritdoc
     */
    protected function initExport()/*: void*/
    {
        $this->setExportFormats([self::EXPORT_CSV, self::EXPORT_EXCEL]);
    }

    /**
     * @inheritdoc
     */
    protected function initFilterFields()/*: void*/
    {
        self::dic()->language()->loadLanguageModule("form");

        $this->filter_fields = [
            "date" => [
                PropertyFormGUI::PROPERTY_CLASS => DateDurationInputGUI::class,
                "setShowTime" => true,
            ],
            "origin_id" => [
                PropertyFormGUI::PROPERTY_CLASS => NumberInputGUI::class,
                "setMinValue" => 0,
            ],
            "origin_object_type" => [
                PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => [
                        "" => "",
                    ] + array_map(
                        function (string $origin_object_type) : string {
                            return self::plugin()->translate("origin_object_type_" . $origin_object_type);
                        }, AROrigin::$object_types
                    ),
            ],
            "status" => [
                PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => [
                        "" => "",
                    ] + ARObject::$available_status,
            ],
            "object_ext_id" => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class,
            ],
            "object_ilias_id" => [
                PropertyFormGUI::PROPERTY_CLASS => NumberInputGUI::class,
                "setMinValue" => 0,
            ],
            "title" => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class,
            ],
            "message" => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class,
            ],
            "level" => [
                PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => [
                        "" => "",
                    ] + array_map(
                        function (int $level) : string {
                            return $this->txt("level_" . $level);
                        }, Log::$levels
                    ),
            ],
            "additional_data" => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function initId()/*: void*/
    {
        $this->setId("hub2_logs");
    }

    /**
     * @inheritdoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("logs"));
    }
}
