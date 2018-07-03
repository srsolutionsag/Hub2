<?php

namespace SRAG\Plugins\Hub2\UI;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use SRAG\Plugins\Hub2\Origin\Config\ICourseOriginConfig;
use SRAG\Plugins\Hub2\Origin\Group\ARGroupOrigin;
use SRAG\Plugins\Hub2\Origin\Properties\GroupOriginProperties;

/**
 * Class GroupOriginConfigFormGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARGroupOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		parent::addSyncConfig();
		$te = new ilTextInputGUI($this->pl->txt('grp_prop_node_noparent'), $this->conf(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$te->setInfo($this->pl->txt('grp_prop_node_noparent_info'));
		$te->setValue($this->origin->properties()->get(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$this->addItem($te);
	}


	protected function addPropertiesNew() {
		parent::addPropertiesNew();
	}


	protected function addPropertiesUpdate() {
		$cb = new ilCheckboxInputGUI($this->pl->txt('grp_prop_move'), $this->prop(GroupOriginProperties::MOVE_GROUP));
		$cb->setInfo($this->pl->txt('grp_prop_move_info'));
		$this->addItem($cb);

		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new ilRadioGroupInputGUI($this->pl->txt('grp_prop_delete_mode'), $this->prop(GroupOriginProperties::DELETE_MODE));
		$delete->setValue($this->origin->properties()->get(GroupOriginProperties::DELETE_MODE));

		$opt = new ilRadioOption($this->pl->txt('grp_prop_delete_mode_none'), GroupOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);

		$opt = new ilRadioOption($this->pl->txt('grp_prop_delete_mode_close'), GroupOriginProperties::DELETE_MODE_CLOSED);
		$delete->addOption($opt);

		$opt = new ilRadioOption($this->pl->txt('grp_prop_delete_mode_delete'), GroupOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);

		$opt = new ilRadioOption($this->pl->txt('grp_prop_delete_mode_delete_or_close'), GroupOriginProperties::DELETE_MODE_DELETE_OR_CLOSE);
		$opt->setInfo($this->pl->txt('grp_prop_delete_mode_delete_or_close_info'));
		$delete->addOption($opt);

		$opt = new ilRadioOption($this->pl->txt('grp_prop_delete_mode_trash'), GroupOriginProperties::DELETE_MODE_MOVE_TO_TRASH);
		$delete->addOption($opt);

		$this->addItem($delete);
		parent::addPropertiesDelete();
	}
}
