<?php

namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;

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
		parent::addPropertiesDelete();
	}
}
