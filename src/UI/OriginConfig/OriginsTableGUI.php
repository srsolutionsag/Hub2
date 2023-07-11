<?php

namespace srag\Plugins\Hub2\UI\OriginConfig;

use hub2ConfigOriginsGUI;
use ilAdvancedSelectionListGUI;
use ilHub2Plugin;
use ilTable2GUI;
use srag\DIC\Hub2\Exception\DICException;
use srag\Plugins\Hub2\Object\IObjectRepository;
use srag\Plugins\Hub2\Origin\IOriginRepository;

/**
 * Class OriginsTableGUI
 * @package srag\Plugins\Hub2\UI\OriginConfig
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginsTableGUI extends ilTable2GUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var ilHub2Plugin
     */
    private $plugin;
    /**
     * @var int
     */
    protected $a_parent_obj;
    /**
     * @var IOriginRepository
     */
    protected $originRepository;
    /**
     * @var \ILIAS\DI\UIServices
     */
    private $ui;

    /**
     * @param hub2ConfigOriginsGUI $a_parent_obj
     * @param string               $a_parent_cmd
     * @throws DICException
     * @internal param
     */
    public function __construct($a_parent_obj, $a_parent_cmd, IOriginRepository $originRepository)
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
        $this->ui = $DIC->ui();
        $this->plugin = ilHub2Plugin::getInstance();
        $this->originRepository = $originRepository;
        $this->a_parent_obj = $a_parent_obj;
        $this->setPrefix('hub2_');
        $this->setId('origins');
        $this->setTitle($this->plugin->txt('hub_origins'));
        parent::__construct($a_parent_obj, $a_parent_cmd);
        $this->setFormAction($ctrl->getFormAction($a_parent_obj));
        $this->setRowTemplate('tpl.std_row_template.html', 'Services/ActiveRecord');
        $this->initColumns();
        $this->initTableData();
        $this->addCommandButton(
            hub2ConfigOriginsGUI::CMD_DEACTIVATE_ALL,
            $this->plugin->txt('origin_table_button_deactivate_all')
        );
        $this->addCommandButton(
            hub2ConfigOriginsGUI::CMD_ACTIVATE_ALL,
            $this->plugin->txt('origin_table_button_activate_all')
        );
    }

    /**
     *
     */
    protected function initColumns()
    {
        $this->addColumn($this->plugin->txt('origin_table_header_id'), 'id');
        $this->addColumn($this->plugin->txt('origin_table_header_sort'), 'sort');
        $this->addColumn($this->plugin->txt('origin_table_header_active'), 'active');
        $this->addColumn($this->plugin->txt('origin_table_header_title'), 'title');
        $this->addColumn($this->plugin->txt('origin_table_header_description'), 'description');
        $this->addColumn($this->plugin->txt('origin_table_header_usage_type'), 'object_type');
        $this->addColumn($this->plugin->txt('origin_table_header_last_update'), 'last_sync');
        $this->addColumn($this->plugin->txt('origin_table_header_count'), 'n_objects');
        $this->addColumn($this->plugin->txt('common_actions'));
    }

    /**
     *
     */
    protected function initTableData()
    {
        $data = [];
        foreach ($this->originRepository->all() as $origin) {
            $this->ctrl->setParameter($this->parent_obj, 'origin_id', $origin->getId());
            $class = "srag\\Plugins\\Hub2\\Object\\" . ucfirst($origin->getObjectType()) . "\\" . ucfirst(
                $origin->getObjectType()
            ) . "Repository";
            /** @var IObjectRepository $objectRepository */
            $objectRepository = new $class($origin);
            $row = [];
            $row['id'] = $origin->getId();
            $row['sort'] = sprintf("%02d", $origin->getSort());
            $row['active'] = $this->plugin->txt("common_" . ($origin->isActive() ? "yes" : "no"));
            $linked_title = $this->ui->renderer()->render(
                $this->ui->factory()->link()->standard(
                    $origin->getTitle(),
                    $this->ctrl->getLinkTarget(
                        $this->parent_obj,
                        hub2ConfigOriginsGUI::CMD_EDIT_ORGIN
                    )
                )
            );
            $row['title'] = $linked_title;
            $row['description'] = $origin->getDescription();
            $row['object_type'] = $this->plugin->txt("origin_object_type_" . $origin->getObjectType());
            $row['last_sync'] = $origin->getLastRun();
            $row['n_objects'] = $objectRepository->count();
            $data[] = $row;
        }
        $this->setData($data);
        $this->setDefaultOrderField("sort");
        $this->setDefaultOrderDirection("asc");
    }

    /**
     * @param array $a_set
     */
    protected function fillRow($a_set)
    {
        foreach ($a_set as $value) {
            $this->tpl->setCurrentBlock('cell');
            $this->tpl->setVariable('VALUE', is_null($value) ? "&nbsp;" : $value);
            $this->tpl->parseCurrentBlock();
        }
        $actions = new ilAdvancedSelectionListGUI();
        $actions->setId('actions_' . $a_set['id']);
        $actions->setListTitle($this->plugin->txt('common_actions'));
        $this->ctrl->setParameter($this->parent_obj, 'origin_id', $a_set['id']);
        $actions->addItem(
            $this->plugin->txt('common_edit'),
            'edit',
            $this->ctrl
                ->getLinkTarget(
                    $this->parent_obj,
                    hub2ConfigOriginsGUI::CMD_EDIT_ORGIN
                )
        );
        $actions->addItem(
            $this->plugin->txt('common_delete'),
            'delete',
            $this->ctrl
                ->getLinkTarget(
                    $this->parent_obj,
                    hub2ConfigOriginsGUI::CMD_CONFIRM_DELETE
                )
        );
        $actions->addItem(
            $this->plugin->txt('origin_table_button_run'),
            'runOriginSync',
            $this->ctrl
                ->getLinkTarget(
                    $this->parent_obj,
                    hub2ConfigOriginsGUI::CMD_RUN_ORIGIN_SYNC
                )
        );
        $actions->addItem(
            $this->plugin->txt('origin_table_button_run_force_update'),
            'runOriginSyncForceUpdate',
            $this->ctrl
                ->getLinkTarget($this->parent_obj, hub2ConfigOriginsGUI::CMD_RUN_ORIGIN_SYNC_FORCE_UPDATE)
        );
        $actions->addItem(
            $this->plugin->txt('origin_table_button_toggle'),
            hub2ConfigOriginsGUI::CMD_TOGGLE,
            $this->ctrl->getLinkTarget($this->parent_obj, hub2ConfigOriginsGUI::CMD_TOGGLE)
        );
        $this->ctrl->clearParameters($this->parent_obj);
        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable('VALUE', $actions->getHTML());
        $this->tpl->parseCurrentBlock();
    }
}
