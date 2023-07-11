<?php

namespace srag\Plugins\Hub2\UI\Session;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties;
use srag\Plugins\Hub2\Origin\Session\ARSessionOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class SessionOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARSessionOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('sess_prop_move'),
            $this->prop(SessionProperties::MOVE_SESSION)
        );
        $cb->setInfo($this->plugin->txt('sess_prop_move_info'));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt('sess_prop_delete_mode'),
            $this->prop(SessionProperties::DELETE_MODE)
        );
        $delete->setValue($this->origin->properties()->get(SessionProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            $this->plugin->txt('sess_prop_delete_mode_none'),
            SessionProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('sess_prop_delete_mode_delete'),
            SessionProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('sess_prop_delete_mode_trash'),
            SessionProperties::DELETE_MODE_MOVE_TO_TRASH
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
