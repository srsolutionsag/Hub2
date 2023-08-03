<?php

namespace srag\Plugins\Hub2\UI\Log;

use ilDateTime;
use ilHub2Plugin;
use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Log\Repository as LogRepository;
use srag\Plugins\Hub2\UI\Table\TableGUI\TableGUI;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Shortlink\ObjectLinkFactory;

/**
 *
 */
class LogsTableGUI extends \ilTable2GUI
{
    /**
     * @var ilHub2Plugin
     */
    private $plugin;

    private $filtered = [];
    /**
     * @var \srag\Plugins\Hub2\Log\IRepository
     */
    private $log_repo;
    /**
     * @var ObjectLinkFactory
     */
    private $link_factory;
    /**
     * @var \ILIAS\DI\UIServices
     */
    private $ui;

    public function __construct(\hub2LogsGUI $a_parent_obj, $a_parent_cmd)
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
        $this->plugin = ilHub2Plugin::getInstance();
        $this->log_repo = LogRepository::getInstance();
        $this->link_factory = new ObjectLinkFactory();
        $this->ui = $DIC->ui();

        $this->setPrefix('hub2_');
        $this->setId('logs');
        $this->setTitle($this->plugin->txt('logs'));
        parent::__construct($a_parent_obj, $a_parent_cmd);
        $this->setFormAction($ctrl->getFormAction($a_parent_obj));
        $this->setRowTemplate('tpl.std_row_template.html', 'Services/ActiveRecord');
        $this->initFilter();
        $this->initColumns();
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);
        $this->setExportFormats([self::EXPORT_EXCEL]);

        $this->determineLimit();
        if ($this->getLimit() > 999) {
            $this->setLimit(999);
        }
        $this->determineOffsetAndOrder();
        $this->setDefaultOrderDirection("DESC");
        $this->setDefaultOrderField("date");
        $this->initTableData();
    }

    protected function initColumns() : void
    {
        $this->addColumn($this->plugin->txt('logs_date'), 'date');
        $this->addColumn($this->plugin->txt('logs_origin_id')); //, 'origin_id');
        $this->addColumn($this->plugin->txt('logs_origin_object_type')); //, 'origin_object_type');
        $this->addColumn($this->plugin->txt('logs_status')); //, 'status');
        $this->addColumn($this->plugin->txt('logs_object_ext_id')); //, 'object_ext_id');
        $this->addColumn($this->plugin->txt('logs_object_ilias_id')); //, 'object_ilias_id');
        $this->addColumn($this->plugin->txt('logs_level')); //, 'level');
        $this->addColumn($this->plugin->txt('logs_additional_data')); //, 'additional_data');
//        $this->addColumn($this->plugin->txt('data_table_header_actions')); //TODO
    }

    protected function fillRow($a_set)
    {
        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable('VALUE', $a_set->getDate()->get(IL_CAL_DATETIME));
        $this->tpl->parseCurrentBlock();

        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable('VALUE', $a_set->getOriginId());
        $this->tpl->parseCurrentBlock();

        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable(
            'VALUE',
            $this->plugin->txt('origin_object_type_' . AROrigin::$object_types[$a_set->getOriginObjectType()])
        );
        $this->tpl->parseCurrentBlock();

        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable(
            'VALUE',
            $this->plugin->txt('data_table_status_' . ARObject::$available_status[$a_set->getStatus()])
        );
        $this->tpl->parseCurrentBlock();

        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable('VALUE', $a_set->getObjectExtId());
        $this->tpl->parseCurrentBlock();

        $this->tpl->setCurrentBlock('cell');
        $ilias_id = $a_set->getObjectIliasId();
        $this->tpl->setVariable(
            'VALUE',
            $ilias_id === null ? '' : $this->ui->renderer()->render(
                $this->ui->factory()->link()->standard(
                    $ilias_id,
                    $this->link_factory->findByExtId($a_set->getObjectExtId())->getAccessGrantedInternalLink()
                )->withOpenInNewViewport(true)
            )
        );
        $this->tpl->parseCurrentBlock();

        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable('VALUE', $this->plugin->txt('logs_level_' . Log::$levels[$a_set->getLevel()]));
        $this->tpl->parseCurrentBlock();

        $this->tpl->setCurrentBlock('cell');
        $value = $a_set->getAdditionalData();
        $value = get_object_vars($value);
        // stClass to list of key value pairs, seperated by : and newlines
        $value = implode(
            "\n",
            array_map(function ($k, $v) : string {
                return $k . ': ' . $v;
            }, array_keys($value), $value)
        );
        $this->tpl->setVariable('VALUE', $value);
        $this->tpl->parseCurrentBlock();

        // Actions
        // TODO
    }

    public function initFilter() : void
    {
        $this->setDisableFilterHiding(true);

        // Range
        $range = new \ilDateDurationInputGUI($this->plugin->txt('logs_date'), 'date');
        $range->setShowTime(true);
        $range->setStart(null);
        $range->setEnd(null);
        $this->addAndReadFilterItem($range);

        // Origin Select
        $origins = [null => null];
        $factory = new OriginFactory();
        foreach ($factory->getAll() as $origin) {
            $origins[$origin->getId()] = $origin->getTitle();
        }
        $origin_select = new \ilSelectInputGUI($this->plugin->txt('logs_origin_id'), 'origin_id');
        $origin_select->setOptions($origins);
        $this->addAndReadFilterItem($origin_select);

        // Object Type
        $object_type_select = new \ilSelectInputGUI(
            $this->plugin->txt('logs_origin_object_type'),
            'origin_object_type'
        );
        $object_type_select->setOptions([null => null] + AROrigin::$object_types);
        $this->addAndReadFilterItem($object_type_select);

        // Status
        $status_select = new \ilSelectInputGUI($this->plugin->txt('logs_status'), 'status');
        $status_select->setOptions([null => null] + ARObject::$available_status);
        $this->addAndReadFilterItem($status_select);

        // Ext-ID
        $ext_id = new \ilTextInputGUI($this->plugin->txt('logs_object_ext_id'), 'object_ext_id');
        $this->addAndReadFilterItem($ext_id);

        // ILIAS-ID
        $ilias_id = new \ilTextInputGUI($this->plugin->txt('logs_object_ilias_id'), 'object_ilias_id');
        $this->addAndReadFilterItem($ilias_id);

        // Level
        $level_select = new \ilSelectInputGUI($this->plugin->txt('logs_level'), 'level');
        $level_select->setOptions(
            [null => null] + array_map(function ($level) : string {
                return $this->plugin->txt('logs_level_' . $level);
            }, Log::$levels)
        );
        $this->addAndReadFilterItem($level_select);

        // Additional Data
        $additional_data = new \ilTextInputGUI($this->plugin->txt('logs_additional_data'), 'additional_data');
        $this->addAndReadFilterItem($additional_data);
    }

    protected function hasSessionValue(string $field_id) : bool
    {
        // Not set on first visit, false on reset filter, string if is set
        return (isset($_SESSION["form_" . $this->getId()][$field_id]) && $_SESSION["form_" . $this->getId(
            )][$field_id] !== false);
    }

    protected function addAndReadFilterItem(\ilFormPropertyGUI $item) : void
    {
        $this->addFilterItem($item);
        if ($this->hasSessionValue($item->getFieldId())) { // Supports filter default values
            $item->readFromSession();
        }
        switch (true) {
            case ($item instanceof \ilCheckboxInputGUI):
                $this->filtered[$item->getPostVar()] = $item->getChecked();
                break;
            case ($item instanceof \ilDateDurationInputGUI):
                $this->filtered[$item->getPostVar()]["start"] = $item->getStart() === null
                    ? null
                    : $item->getStart()->get(IL_CAL_UNIX);

                $this->filtered[$item->getPostVar()]["end"] = $item->getEnd() === null
                    ? null
                    : $item->getEnd()->get(IL_CAL_UNIX);
                break;
            default:
                $this->filtered[$item->getPostVar()] = $item->getValue();
                break;
        }
    }

    private function initTableData() : void
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setDefaultOrderField("date");
        $this->setDefaultOrderDirection("desc");

        // Fix stupid ilTable2GUI !!! ...
        $this->determineLimit();
        $this->determineOffsetAndOrder();

        $filter = $this->filtered;

        $title = $filter["title"];
        $message = $filter["message"];
        $date_start = $filter["date"]["start"];
        $date_start = empty($date_start) ? null : new ilDateTime((int) $date_start, IL_CAL_UNIX);
        $date_end = $filter["date"]["end"];
        $date_end = empty($date_end) ? null : new ilDateTime((int) $date_end, IL_CAL_UNIX);
        $level = $filter["level"];
        $level = empty($level) ? null : (int) $level;
        $origin_id = $filter["origin_id"];
        $origin_id = empty($origin_id) ? null : (int) $origin_id;
        $origin_object_type = $filter["origin_object_type"];
        $object_ext_id = $filter["object_ext_id"];
        $object_ilias_id = $filter["object_ilias_id"];
        $object_ilias_id = empty($object_ilias_id) ? null : (int) $object_ilias_id;
        $additional_data = $filter["additional_data"] ?? '';
        $status = (int) $filter["status"];

        $this->setData(
            $this->log_repo
                ->getLogs(
                    $this->getOrderField(),
                    $this->getOrderDirection(),
                    $this->getOffset(),
                    $this->getLimit(),
                    $title,
                    $message,
                    $date_start,
                    $date_end,
                    $level,
                    $origin_id,
                    $origin_object_type,
                    $object_ext_id,
                    $object_ilias_id,
                    $additional_data,
                    $status
                )
        );

        $this->setMaxCount(
            $this->log_repo
                ->getLogsCount(
                    $title,
                    $message,
                    $date_start,
                    $date_end,
                    $level,
                    $origin_id,
                    $origin_object_type,
                    $object_ext_id,
                    $object_ilias_id,
                    $additional_data,
                    $status
                )
        );
    }

    protected function buildTableDataArray(
        string $sort_by,
        ?string $title,
        ?string $message,
        ?ilDateTime $date_start,
        ?ilDateTime $date_end,
        ?int $level,
        ?int $origin_id,
        ?string $origin_object_type,
        ?string $object_ext_id,
        ?int $object_ilias_id,
        ?string $additional_data,
        ?int $status
    ): array {
        return array_map(function (ILog $log): array {
            return [
                'object' => $log,
            ];
        }, $this->log_repo
            ->getLogs(
                $sort_by,
                $this->getOrderDirection(),
                $this->getOffset(),
                $this->getLimit(),
                $title,
                $message,
                $date_start,
                $date_end,
                $level,
                $origin_id,
                $origin_object_type,
                $object_ext_id,
                $object_ilias_id,
                $additional_data,
                $status
            ));
    }
}
