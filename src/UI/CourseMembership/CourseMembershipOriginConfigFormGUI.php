<?php

namespace srag\Plugins\Hub2\UI\CourseMembership;

use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Properties\CourseMembership\CourseMembershipProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class CourseMembershipOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARCourseMembershipOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt('crs_prop_delete_mode'),
            $this->prop(CourseMembershipProperties::DELETE_MODE)
        );
        $delete->setValue($this->origin->properties()->get(CourseMembershipProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            $this->plugin->txt('crs_prop_delete_mode_none'),
            CourseMembershipProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);
        $opt = new ilRadioOption(
            $this->plugin->txt('crs_membership_prop_delete_mode_delete'),
            CourseMembershipProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);
        $this->addItem($delete);
    }
}
