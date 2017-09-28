<?php
namespace SRAG\Hub2\UI;

use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Origin\Session\ARSessionOrigin;
use SRAG\Hub2\Origin\Config\ISessionOriginConfig;
use SRAG\Hub2\Origin\Config\SessionOriginConfig;
use SRAG\Hub2\Origin\Properties\IOriginProperties;
use SRAG\Hub2\Origin\Properties\SessionOriginProperties;

/**
 * Class CourseMembershipOriginConfigFormGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var \SRAG\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin
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