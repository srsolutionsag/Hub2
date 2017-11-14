<?php namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Origin\Session\ARSessionOrigin;

/**
 * Class UserOriginConfigFormGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARSessionOrigin
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