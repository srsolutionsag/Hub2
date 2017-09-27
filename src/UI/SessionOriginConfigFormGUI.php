<?php namespace SRAG\Hub2\UI;

use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Origin\Session\ARSessionOrigin;
use SRAG\Hub2\Origin\Config\ISessionOriginConfig;
use SRAG\Hub2\Origin\Config\SessionOriginConfig;
use SRAG\Hub2\Origin\Properties\IOriginProperties;
use SRAG\Hub2\Origin\Properties\SessionOriginProperties;

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