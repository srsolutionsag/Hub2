<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\UI\CourseMembership;

use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Properties\CourseMembership\CourseMembershipProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\Origin\IOrigin;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class CourseMembershipOriginConfigFormGUI extends OriginConfigFormGUI
{
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt('crs_prop_delete_mode'),
            $this->prop(CourseMembershipProperties::DELETE_MODE)
        );
        $delete->setValue(
            $this->origin->properties()->get(
                CourseMembershipProperties::DELETE_MODE
            ) ?? CourseMembershipProperties::DELETE_MODE_NONE
        );

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
