<?php

namespace srag\Plugins\Hub2\UI\OrgUnitMembership;

use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\Plugins\Hub2\Origin\OrgUnitMembership\AROrgUnitMembershipOrigin;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership\IOrgUnitMembershipProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class OrgUnitMembershipOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitMembershipOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var AROrgUnitMembershipOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt("orgunitmembership_delete_mode"),
            $this->prop(IOrgUnitMembershipProperties::DELETE_MODE)
        );
        $opt = new ilRadioOption(
            $this->plugin->txt("orgunitmembership_delete_mode_none"),
            IOrgUnitMembershipProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);
        $opt = new ilRadioOption(
            $this->plugin->txt("orgunitmembership_delete_mode_delete"),
            IOrgUnitMembershipProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);
        $delete->setValue($this->origin->properties()->get(IOrgUnitMembershipProperties::DELETE_MODE));
        $this->addItem($delete);
    }
}
