<?php

namespace SRAG\Plugins\Hub2\UI;

/**
 * Class SessionMembershipOriginConfigFormGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var \SRAG\Plugins\Hub2\Origin\SessionMembership\ARSessionMembershipOrigin
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