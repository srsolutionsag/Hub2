<?php

namespace srag\Plugins\Hub2\UI;

use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Properties\CourseMembershipOriginProperties;

/**
 * Class CourseMembershipOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI
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
		$delete = new ilRadioGroupInputGUI(self::plugin()
			->translate('crs_prop_delete_mode'), $this->prop(CourseMembershipOriginProperties::DELETE_MODE));
		$delete->setValue($this->origin->properties()->get(CourseMembershipOriginProperties::DELETE_MODE));

		$opt = new ilRadioOption(self::plugin()->translate('crs_prop_delete_mode_none'), CourseMembershipOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);
		$opt = new ilRadioOption(self::plugin()
			->translate('crs_membership_prop_delete_mode_delete'), CourseMembershipOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);
		$this->addItem($delete);

		parent::addPropertiesDelete();
	}
}
