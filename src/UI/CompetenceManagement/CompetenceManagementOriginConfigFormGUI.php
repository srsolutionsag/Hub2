<?php

namespace srag\Plugins\Hub2\UI\CompetenceManagement;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\CompetenceManagement\ARCompetenceManagementOrigin;
use srag\Plugins\Hub2\Origin\Config\CompetenceManagement\ICompetenceManagementOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\ICompetenceManagementProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class CompetenceManagementOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CompetenceManagementOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARCompetenceManagementOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $id_if_no_parent_id = new ilTextInputGUI(
            $this->plugin->txt("competencemanagement_id_if_no_parent_id"),
            $this->conf(ICompetenceManagementOriginConfig::ID_IF_NO_PARENT_ID)
        );
        $id_if_no_parent_id->setInfo($this->plugin->txt("competencemanagement_id_if_no_parent_id_info"));
        $id_if_no_parent_id->setValue($this->origin->config()->getIdIfNoParentId());
        $this->addItem($id_if_no_parent_id);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt("competencemanagement_move"),
            $this->prop(ICompetenceManagementProperties::MOVE)
        );
        $cb->setChecked($this->origin->properties()->get(ICompetenceManagementProperties::MOVE));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt("competencemanagement_delete_mode"),
            $this->prop(ICompetenceManagementProperties::DELETE_MODE)
        );
        $opt = new ilRadioOption(
            $this->plugin->txt("competencemanagement_delete_mode_none"),
            ICompetenceManagementProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);
        $opt = new ilRadioOption(
            $this->plugin->txt("competencemanagement_delete_mode_delete"),
            ICompetenceManagementProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);
        $delete->setValue($this->origin->properties()->get(ICompetenceManagementProperties::DELETE_MODE));
        $this->addItem($delete);
    }
}
