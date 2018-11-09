<?php

namespace srag\Plugins\Hub2\UI;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Category\ARCategoryOrigin;
use srag\Plugins\Hub2\Origin\Config\ICategoryOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CategoryOriginProperties;

/**
 * Class CategoryOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARCategoryOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		parent::addSyncConfig();

		$te = new ilTextInputGUI(self::plugin()
			->translate('cat_prop_base_node_ilias'), $this->conf(ICategoryOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$te->setInfo(self::plugin()->translate('cat_prop_base_node_ilias_info'));
		$te->setValue($this->origin->config()->getParentRefIdIfNoParentIdFound());
		$this->addItem($te);

		$te = new ilTextInputGUI(self::plugin()
			->translate('cat_prop_base_node_external'), $this->conf(ICategoryOriginConfig::EXT_ID_NO_PARENT_ID_FOUND));
		$te->setInfo(self::plugin()->translate('cat_prop_base_node_external_info'));
		$te->setValue($this->origin->config()->getExternalParentIdIfNoParentIdFound());
		$this->addItem($te);
	}


	protected function addPropertiesNew() {
		parent::addPropertiesNew();

		$cb = new ilCheckboxInputGUI(self::plugin()->translate('cat_prop_set_news'), $this->prop(CategoryOriginProperties::SHOW_NEWS));
		$cb->setChecked($this->origin->properties()->get(CategoryOriginProperties::SHOW_NEWS));
		$this->addItem($cb);

		$cb = new ilCheckboxInputGUI(self::plugin()->translate('cat_prop_set_infopage'), $this->prop(CategoryOriginProperties::SHOW_INFO_TAB));
		$cb->setChecked($this->origin->properties()->get(CategoryOriginProperties::SHOW_INFO_TAB));
		$this->addItem($cb);
	}


	protected function addPropertiesUpdate() {
		$cb = new ilCheckboxInputGUI(self::plugin()->translate('cat_prop_move'), $this->prop(CategoryOriginProperties::MOVE_CATEGORY));
		$cb->setChecked($this->origin->properties()->get(CategoryOriginProperties::MOVE_CATEGORY));
		$this->addItem($cb);

		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new ilRadioGroupInputGUI(self::plugin()->translate('cat_prop_delete_mode'), $this->prop(CategoryOriginProperties::DELETE_MODE));
		$delete->setValue($this->origin->properties()->get(CategoryOriginProperties::DELETE_MODE));

		$opt = new ilRadioOption(self::plugin()->translate('cat_prop_delete_mode_none'), CategoryOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('cat_prop_delete_mode_inactive', "", [
			self::plugin()->translate('com_prop_mark_deleted_text')
		]), CategoryOriginProperties::DELETE_MODE_MARK);
		$delete->addOption($opt);

		$te = new ilTextInputGUI(self::plugin()
			->translate('cat_prop_delete_mode_inactive_text'), $this->prop(CategoryOriginProperties::DELETE_MODE_MARK_TEXT));
		$te->setValue($this->origin->properties()->get(CategoryOriginProperties::DELETE_MODE_MARK_TEXT));
		$opt->addSubItem($te);

		$opt = new ilRadioOption(self::plugin()->translate('cat_prop_delete_mode_delete'), CategoryOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);

		$this->addItem($delete);

		parent::addPropertiesDelete();
	}
}
