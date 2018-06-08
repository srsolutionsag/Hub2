<?php

namespace SRAG\Plugins\Hub2\UI;

/**
 * Class OrgUnitOriginConfigFormGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var \SRAG\Plugins\Hub2\Origin\OrgUnit\AROrgUnitOrigin
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
