<?php

namespace srag\Plugins\Hub2\UI\Group;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\Course\ICourseOriginConfig;
use srag\Plugins\Hub2\Origin\Group\ARGroupOrigin;
use srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class GroupOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARGroupOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $te = new ilTextInputGUI(
            $this->plugin->txt('grp_prop_node_noparent'),
            $this->conf(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo($this->plugin->txt('grp_prop_node_noparent_info'));
        $te->setValue($this->origin->config()->get(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
        $this->addItem($te);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('grp_prop_move'),
            $this->prop(GroupProperties::MOVE_GROUP)
        );
        $cb->setInfo($this->plugin->txt('grp_prop_move_info'));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt('grp_prop_delete_mode'),
            $this->prop(GroupProperties::DELETE_MODE)
        );
        $delete->setValue($this->origin->properties()->get(GroupProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            $this->plugin->txt('grp_prop_delete_mode_none'),
            GroupProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('grp_prop_delete_mode_close'),
            GroupProperties::DELETE_MODE_CLOSED
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('grp_prop_delete_mode_delete'),
            GroupProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('grp_prop_delete_mode_delete_or_close'),
            GroupProperties::DELETE_MODE_DELETE_OR_CLOSE
        );
        $opt->setInfo($this->plugin->txt('grp_prop_delete_mode_delete_or_close_info'));
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('grp_prop_delete_mode_trash'),
            GroupProperties::DELETE_MODE_MOVE_TO_TRASH
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
