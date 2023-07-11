<?php

namespace srag\Plugins\Hub2\UI\Data;

use hub2DataGUI;
use hub2LogsGUI;
use ilCheckboxInputGUI;
use ilExcel;
use ilFormPropertyGUI;
use ilHub2Plugin;
use ilSelectInputGUI;
use ilTable2GUI;
use ilTemplateException;
use ilTextInputGUI;
use ReflectionException;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\CompetenceManagement\ARCompetenceManagement;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Shortlink\ObjectLinkFactory;

/**
 * Class OriginsTableGUI
 * @package srag\Plugins\Hub2\UI\Data
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DataTableGUI extends ilTable2GUI
{
    public const F_ORIGIN_ID = 'origin_id';
    public const F_EXT_ID = 'ext_id';
    /**
     * @var ARObject[]
     */
    public static $classes
        = [
            ARUser::class,
            ARCourse::class,
            ARGroup::class,
            ARSession::class,
            ARCategory::class,
            ARCourseMembership::class,
            ARGroupMembership::class,
            ARSessionMembership::class,
            AROrgUnit::class,
            AROrgUnitMembership::class,
            ARCompetenceManagement::class,
        ];
    /**
     * @var ilHub2Plugin
     */
    protected $plugin;
    /**
     * @var ObjectLinkFactory
     */
    protected $originLinkfactory;
    /**
     * @var array
     */
    protected $filtered = [];
    /**
     * @var OriginFactory
     */
    protected $originFactory;
    /**
     * @var int
     */
    protected $a_parent_obj;
    /**
     * @var IOriginRepository
     */
    protected $originRepository;
    /**
     * @var \ilDBInterface
     */
    private $db;
    /**
     * @var \ILIAS\DI\UIServices
     */
    private $ui;

    /**
     * DataTableGUI constructor
     * @param string $a_parent_cmd
     */
    public function __construct(hub2DataGUI $a_parent_obj, $a_parent_cmd)
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
        $this->db = $DIC->database();
        $this->ui = $DIC->ui();
        $this->plugin = ilHub2Plugin::getInstance();
        $this->a_parent_obj = $a_parent_obj;
        $this->originFactory = new OriginFactory();
        $this->originLinkfactory = new ObjectLinkFactory();
        $this->setPrefix('hub2_');
        $this->setId('data');
        $this->setTitle($this->plugin->txt('subtab_data'));
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
        $this->setDefaultOrderField("processed_date");
        $this->initTableData();
    }

    /**
     * @inheritdoc
     */
    public function initFilter() : void
    {
        $this->setDisableFilterHiding(true);

        $origin = new ilSelectInputGUI($this->plugin->txt('data_table_header_origin_id'), 'origin_id');
        $origin->setOptions($this->getAvailableOrigins());
        $this->addAndReadFilterItem($origin);

        // Status
        $status = new ilSelectInputGUI($this->plugin->txt('data_table_header_status'), 'status');

        $options = ["" => ""] + array_map(
            function (string $txt) : string {
                return $this->plugin->txt("data_table_status_" . $txt);
            },
            ARObject::$available_status
        ) + [
                "!" . IObject::STATUS_IGNORED => $this->plugin->txt("data_table_status_not_ignored"),
            ];

        $status->setOptions($options);
        $status->setValue("!" . IObject::STATUS_IGNORED);
        $this->addAndReadFilterItem($status);

        $ext_id = new ilTextInputGUI($this->plugin->txt('data_table_header_ext_id'), 'ext_id');
        $this->addAndReadFilterItem($ext_id);

        $data = new ilTextInputGUI($this->plugin->txt('data_table_header_data'), 'data');
        $this->addAndReadFilterItem($data);
    }

    protected function hasSessionValue(string $field_id) : bool
    {
        // Not set on first visit, false on reset filter, string if is set
        return (isset($_SESSION["form_" . $this->getId()][$field_id]) && $_SESSION["form_" . $this->getId(
            )][$field_id] !== false);
    }

    protected function addAndReadFilterItem(ilFormPropertyGUI $item)
    {
        $this->addFilterItem($item);
        if ($this->hasSessionValue($item->getFieldId())) { // Supports filter default values
            $item->readFromSession();
        }
        $this->filtered[$item->getPostVar()] = $item instanceof ilCheckboxInputGUI ? $item->getChecked(
        ) : $item->getValue();
    }

    /**
     *
     */
    protected function initColumns()
    {
        foreach ($this->getFields() as $field) {
            $this->addColumn($this->plugin->txt('data_table_header_' . $field), $field);
        }
        $this->addColumn($this->plugin->txt('data_table_header_actions'));
    }

    /**
     *
     */
    protected function initTableData()
    {
        $data = [];

        $where_query = " WHERE true = true"; // TODO: ???
        foreach ($this->filtered as $postvar => $value) {
            if (!$postvar || !$value) {
                continue;
            }
            $where_query .= " AND ";
            switch ($postvar) {
                case 'data':
                case 'ext_id':
                    $where_query .= $postvar . " LIKE '%" . $value . "%'";
                    break;
                case "status":
                    if (!empty($value) && $value[0] === "!") {
                        $value = substr($value, 1);
                        $where_query .= $postvar . " != " . $value;
                    } else {
                        $where_query .= $postvar . " = " . $value;
                    }
                    break;
                default:
                    $where_query .= $postvar . " = " . $value;
                    break;
            }
        }

        $union_query = "";
        $columns = implode(", ", $this->getFields());
        $columns = rtrim($columns, ", ");
        foreach (self::$classes as $class) {
            $union_query .= "SELECT $columns FROM " . $class::TABLE_NAME . $where_query . " UNION ";
        }
        $union_query = rtrim($union_query, "UNION ");

        $order_field = $this->getOrderField() ?: $this->getDefaultOrderField();
        $order_by_query = " ORDER BY " . $order_field . " " . $this->getOrderDirection();

        $query = $union_query . $order_by_query;
        $result = $this->db->query($query);
        while ($row = $result->fetchRow()) {
            $data[] = $row;
        }
        $this->setMaxCount(count($data));
        $data = array_slice($data, $this->getOffset(), $this->getLimit());
        $this->setData($data);
    }

    /**
     * @param array $a_set
     * @throws ReflectionException
     * @throws ilTemplateException
     * @throws DICException
     */
    protected function fillRow($a_set)
    {
        $this->ctrl->setParameter($this->parent_obj, self::F_EXT_ID, $a_set[self::F_EXT_ID]);
        $this->ctrl->setParameter($this->parent_obj, self::F_ORIGIN_ID, $a_set[self::F_ORIGIN_ID]);

        $this->ctrl->setParameterByClass(hub2LogsGUI::class, self::F_EXT_ID, $a_set[self::F_EXT_ID]);
        $this->ctrl->setParameterByClass(hub2LogsGUI::class, self::F_ORIGIN_ID, $a_set[self::F_ORIGIN_ID]);

        $origin = $this->originFactory->getById($a_set[self::F_ORIGIN_ID]);

        foreach ($a_set as $key => $value) {
            $this->tpl->setCurrentBlock('cell');
            switch ($key) {
                case 'status':
                    $this->tpl->setVariable(
                        'VALUE',
                        $this->plugin->txt("data_table_status_" . ARObject::$available_status[$value])
                    );
                    break;
                case self::F_EXT_ID:
                    $this->tpl->setVariable('VALUE', $value);
                    break;
                case "ilias_id":
                    $this->tpl->setVariable(
                        'VALUE',
                        $this->renderILIASLinkForIliasId($value, $a_set[self::F_EXT_ID], $origin)
                    );
                    break;
                case self::F_ORIGIN_ID:
                    if (!$origin instanceof \srag\Plugins\Hub2\Origin\IOrigin) {
                        $this->tpl->setVariable('VALUE', " " . $this->plugin->txt("origin_deleted"));
                    } else {
                        $this->tpl->setVariable('VALUE', $origin->getTitle());
                    }
                    break;
                default:
                    $this->tpl->setVariable('VALUE', $value ?: "&nbsp;");
                    break;
            }

            $this->tpl->parseCurrentBlock();
        }

        $modal = $this->ui->factory()->modal()->roundtrip(
            $a_set[self::F_EXT_ID],
            $this->ui->factory()->legacy('')
        )->withAsyncRenderUrl(
            $this->ctrl->getLinkTarget(
                $this->parent_obj,
                'renderData',
                '',
                true
            )
        );

        $items = [
            $this->ui->factory()->button()->shy(
                $this->plugin->txt("data_table_header_data"),
                $modal->getShowSignal()
            ),
            $this->ui->factory()->button()->shy(
                $this->plugin->txt("logs_show_logs"),
                $this->ctrl->getLinkTargetByClass(hub2LogsGUI::class, hub2LogsGUI::CMD_SHOW_LOGS_OF_EXT_ID)
            ),
        ];
        $actions_ = $this->ui->factory()->dropdown()->standard($items);

        $actions_html = $this->ui->renderer()->render($actions_);

        // Use a fake button to use clickable open modal on selection list. Replace the id with the button id
        $button = $this->ui->factory()->button()->shy("", "#")->withOnClick($modal->getShowSignal());
        $this->ui->renderer()->render($button);
        /*preg_match('/id="([a-z0-9_]+)"/', $button_html, $button_id);
        if (is_array($button_id) && count($button_id) > 1) {
            $button_id = $button_id[1];

            $actions_html = str_replace('id="asl_view"', 'id="' . $button_id . '"', $actions_html);
        }*/

        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable('VALUE', implode('', [$actions_html, $this->ui->renderer()->render($modal)]));
        $this->tpl->parseCurrentBlock();

        $this->ctrl->clearParameters($this->parent_obj);
    }

    /**
     * @param int $a_row
     */
    protected function fillHeaderExcel(ilExcel $a_excel, &$a_row)
    {
        $col = 0;

        foreach ($this->getFields() as $column) {
            $a_excel->setCell($a_row, $col, $this->plugin->txt('data_table_header_' . $column));
            $col++;
        }

        $a_excel->setBold("A" . $a_row . ":" . $a_excel->getColumnCoord($col - 1) . $a_row);
    }

    /**
     * @param int   $row
     * @param array $result
     */
    protected function fillRowExcel(
        ilExcel $excel, /*int*/
        &$row, /*array*/
        $result
    ) {/*: void*/

        $col = 0;
        foreach ($result as $key => $value) {
            switch ($key) {
                case 'status':
                    $excel->setCell(
                        $row,
                        $col,
                        $this->plugin->txt("data_table_status_" . ARObject::$available_status[$value])
                    );
                    break;
                default:
                    $excel->setCell($row, $col, $value);
                    break;
            }
            $col++;
        }
    }

    /**
     * @param int          $ilias_id
     * @param IOrigin|null $origin
     */
    protected function renderILIASLinkForIliasId($ilias_id, string $ext_id, IOrigin $origin = null) : string
    {
        if (!$origin instanceof \srag\Plugins\Hub2\Origin\IOrigin) {
            return (string) $ilias_id;
        }

        $link = $this->originLinkfactory->findByExtIdAndOrigin($ext_id, $origin);
        if ($link->doesObjectExist()) {
            $link_factory = $this->ui->factory()->link();

            return $this->ui->renderer()->render(
                $link_factory->standard(
                    $ilias_id,
                    $link->getAccessGrantedInternalLink()
                )->withOpenInNewViewport(true)
            );
        } else {
            return (string) $ilias_id;
        }
    }

    protected function getFields() : array
    {
        return [
            self::F_ORIGIN_ID,
            self::F_EXT_ID,
            'delivery_date',
            'processed_date',
            'ilias_id',
            'status',
            'period',
        ];
    }

    /**
     * @return array
     * @throws DICException
     */
    private function getAvailableOrigins()
    {
        static $origins;
        if (is_array($origins)) {
            return $origins;
        }

        $origins = [0 => $this->plugin->txt("data_table_all")];
        foreach ($this->originFactory->getAll() as $origin) {
            $origins[$origin->getId()] = $origin->getTitle();
        }

        return $origins;
    }
}
