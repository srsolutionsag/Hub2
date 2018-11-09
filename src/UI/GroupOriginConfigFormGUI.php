<?php

namespace srag\Plugins\Hub2\UI;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\ICourseOriginConfig;
use srag\Plugins\Hub2\Origin\Group\ARGroupOrigin;
use srag\Plugins\Hub2\Origin\Properties\GroupOriginProperties;

/**
 * Class GroupOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARGroupOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		parent::addSyncConfig();
		$te = new ilTextInputGUI(self::plugin()->translate('grp_prop_node_noparent'), $this->conf(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$te->setInfo(self::plugin()->translate('grp_prop_node_noparent_info'));
		$te->setValue($this->origin->properties()->get(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$this->addItem($te);
	}


	protected function addPropertiesNew() {
		parent::addPropertiesNew();
	}


	protected function addPropertiesUpdate() {
		$cb = new ilCheckboxInputGUI(self::plugin()->translate('grp_prop_move'), $this->prop(GroupOriginProperties::MOVE_GROUP));
		$cb->setInfo(self::plugin()->translate('grp_prop_move_info'));
		$this->addItem($cb);

		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new ilRadioGroupInputGUI(self::plugin()->translate('grp_prop_delete_mode'), $this->prop(GroupOriginProperties::DELETE_MODE));
		$delete->setValue($this->origin->properties()->get(GroupOriginProperties::DELETE_MODE));

		$opt = new ilRadioOption(self::plugin()->translate('grp_prop_delete_mode_none'), GroupOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('grp_prop_delete_mode_close'), GroupOriginProperties::DELETE_MODE_CLOSED);
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('grp_prop_delete_mode_delete'), GroupOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()
			->translate('grp_prop_delete_mode_delete_or_close'), GroupOriginProperties::DELETE_MODE_DELETE_OR_CLOSE);
		$opt->setInfo(self::plugin()->translate('grp_prop_delete_mode_delete_or_close_info'));
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('grp_prop_delete_mode_trash'), GroupOriginProperties::DELETE_MODE_MOVE_TO_TRASH);
		$delete->addOption($opt);

		$this->addItem($delete);
		parent::addPropertiesDelete();
	}
}
