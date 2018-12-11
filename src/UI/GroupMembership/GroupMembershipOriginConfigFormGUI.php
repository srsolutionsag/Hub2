<?php

namespace srag\Plugins\Hub2\UI\GroupMembership;

use srag\Plugins\Hub2\Origin\GroupMembership\ARGroupMembershipOrigin;
use srag\Plugins\Hub2\UI\OriginConfigFormGUI;
use ilCheckboxInputGUI;

/**
 * Class GroupMembershipOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARGroupMembershipOrigin
	 */
	protected $origin;


	/**
	 * @inheritdoc
	 */
	protected function addSyncConfig() {
		parent::addSyncConfig();

        $item = $this->getItemByPostVar(self::POST_VAR_ADHOC);

        $subitem = new ilCheckboxInputGUI(self::plugin()->translate("origin_form_field_adhoc_parent_scope"), "adhoc_parent_scope");
        $subitem->setChecked($this->origin->isAdhocParentScope());
        $subitem->setInfo(self::plugin()->translate("origin_form_field_adhoc_parent_scope_info"));
        $item->addSubItem($subitem);
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
