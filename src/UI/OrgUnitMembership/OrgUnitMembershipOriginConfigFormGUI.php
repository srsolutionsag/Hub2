<?php

namespace srag\Plugins\Hub2\UI\OrgUnit;

use srag\Plugins\Hub2\Origin\OrgUnitMembership\AROrgUnitMembershipOrigin;
use srag\Plugins\Hub2\UI\OriginConfigFormGUI;

/**
 * Class OrgUnitMembershipOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI\OrgUnit
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var AROrgUnitMembershipOrigin
	 */
	protected $origin;


	/**
	 * @inheritdoc
	 */
	protected function addSyncConfig() {
		parent::addSyncConfig();
	}


	/**
	 * @inheritdoc
	 */
	protected function addPropertiesNew() {
		parent::addPropertiesNew();
	}


	/**
	 * @inheritdoc
	 */
	protected function addPropertiesUpdate() {
		parent::addPropertiesUpdate();
	}


	/**
	 * @inheritdoc
	 */
	protected function addPropertiesDelete() {
		parent::addPropertiesDelete();
	}
}