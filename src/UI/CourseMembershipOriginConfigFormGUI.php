<?php

namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use SRAG\Plugins\Hub2\Origin\Properties\CourseMembershipOriginProperties;

/**
 * Class CourseMembershipOriginConfigFormGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARCourseMembershipOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		parent::addSyncConfig();
	}


	protected function addPropertiesNew() {
		parent::addPropertiesNew();
	}


	protected function addPropertiesUpdate() {
		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new \ilRadioGroupInputGUI($this->pl->txt('crs_prop_delete_mode'), $this->prop(CourseMembershipOriginProperties::DELETE_MODE));
		$delete->setValue($this->origin->properties()->get(CourseMembershipOriginProperties::DELETE_MODE));

		$opt = new \ilRadioOption($this->pl->txt('crs_prop_delete_mode_none'), CourseMembershipOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);
		$opt = new \ilRadioOption($this->pl->txt('crs_membership_prop_delete_mode_delete'), CourseMembershipOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);
		$this->addItem($delete);

		parent::addPropertiesDelete();
	}
}
