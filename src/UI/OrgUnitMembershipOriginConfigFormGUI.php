<?php

namespace srag\Plugins\Hub2\UI;

use srag\Plugins\Hub2\Origin\OrgUnitMembership\AROrgUnitMembershipOrigin;

/**
 * Class OrgUnitMembershipOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
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
