<?php

namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Origin\SessionMembership\ARSessionMembershipOrigin;

/**
 * Class SessionMembershipOriginConfigFormGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARSessionMembershipOrigin
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
