<?php
namespace SRAG\Plugins\Hub2\UI;

/**
 * Class GroupMembershipOriginConfigFormGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var \SRAG\Plugins\Hub2\Origin\GroupMembership\ARGroupMembershipOrigin
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