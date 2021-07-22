<?php

namespace srag\Plugins\Hub2\UI\OrgUnit;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\OrgUnit\AROrgUnitOrigin;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\IOrgUnitProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class OrgUnitOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitOriginConfigFormGUI extends OriginConfigFormGUI
{

    /**
     * @var AROrgUnitOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $ref_id_if_no_parent_id = new ilTextInputGUI(
            self::plugin()
                ->translate("orgunit_ref_id_if_no_parent_id"), $this->conf(IOrgUnitOriginConfig::REF_ID_IF_NO_PARENT_ID)
        );
        $ref_id_if_no_parent_id->setInfo(self::plugin()->translate("orgunit_ref_id_if_no_parent_id_info"));
        $ref_id_if_no_parent_id->setValue($this->origin->config()->getRefIdIfNoParentId());
        $this->addItem($ref_id_if_no_parent_id);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesNew()
    {
        parent::addPropertiesNew();
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(self::plugin()->translate("orgunit_move"), $this->prop(IOrgUnitProperties::MOVE));
        $cb->setChecked($this->origin->properties()->get(IOrgUnitProperties::MOVE));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(self::plugin()->translate("orgunit_delete_mode"),
            $this->prop(IOrgUnitProperties::DELETE_MODE));
        $opt = new ilRadioOption(self::plugin()->translate("orgunit_delete_mode_none"),
            IOrgUnitProperties::DELETE_MODE_NONE);
        $delete->addOption($opt);
        $opt = new ilRadioOption(self::plugin()->translate("orgunit_delete_mode_delete"),
            IOrgUnitProperties::DELETE_MODE_DELETE);
        $delete->addOption($opt);
        $delete->setValue($this->origin->properties()->get(IOrgUnitProperties::DELETE_MODE));
        $this->addItem($delete);
    }
}
