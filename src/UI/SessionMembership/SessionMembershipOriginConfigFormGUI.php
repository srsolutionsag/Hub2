<?php

namespace srag\Plugins\Hub2\UI\SessionMembership;

use srag\Plugins\Hub2\Origin\SessionMembership\ARSessionMembershipOrigin;
use srag\Plugins\Hub2\UI\OriginConfigFormGUI;
use ilCheckboxInputGUI;

/**
 * Class SessionMembershipOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARSessionMembershipOrigin
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
