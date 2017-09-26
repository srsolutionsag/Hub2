<?php namespace SRAG\Hub2\UI;

use SRAG\Hub2\Origin\ARCategoryOrigin;
use SRAG\Hub2\Origin\Config\ICategoryOriginConfig;
use SRAG\Hub2\Origin\Properties\CategoryOriginProperties;

/**
 * Class CategoryOriginConfigFormGUI
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\UI
 */
class CategoryOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARCategoryOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		parent::addSyncConfig();

		$te = new \ilTextInputGUI($this->pl->txt('cat_prop_base_node_ilias'), $this->conf(ICategoryOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$te->setInfo($this->pl->txt('cat_prop_base_node_ilias_info'));
		$te->setValue($this->origin->config()->getParentRefIdIfNoParentIdFound());
		$this->addItem($te);

		$te = new \ilTextInputGUI($this->pl->txt('cat_prop_base_node_external'), $this->conf(ICategoryOriginConfig::EXT_ID_NO_PARENT_ID_FOUND));
		$te->setInfo($this->pl->txt('cat_prop_base_node_external_info'));
		$te->setValue($this->origin->config()->getExternalParentIdIfNoParentIdFound());
		$this->addItem($te);
	}


	protected function addPropertiesNew() {
		parent::addPropertiesNew();

		$cb = new \ilCheckboxInputGUI($this->pl->txt('cat_prop_set_news'), $this->prop(CategoryOriginProperties::SHOW_NEWS));
		$cb->setChecked($this->origin->properties()->get(CategoryOriginProperties::SHOW_NEWS));
		$this->addItem($cb);

		$cb = new \ilCheckboxInputGUI($this->pl->txt('cat_prop_set_infopage'), $this->prop(CategoryOriginProperties::SHOW_INFO_TAB));
		$cb->setChecked($this->origin->properties()->get(CategoryOriginProperties::SHOW_INFO_TAB));
		$this->addItem($cb);
	}


	protected function addPropertiesUpdate() {
		$cb = new \ilCheckboxInputGUI($this->pl->txt('cat_prop_move'), $this->prop(CategoryOriginProperties::MOVE_CATEGORY));
		$cb->setChecked($this->origin->properties()->get(CategoryOriginProperties::MOVE_CATEGORY));
		$this->addItem($cb);

		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new \ilRadioGroupInputGUI($this->pl->txt('cat_prop_delete_mode'), $this->prop(CategoryOriginProperties::DELETE_MODE));
		$delete->setValue($this->origin->properties()->get(CategoryOriginProperties::DELETE_MODE));

		$opt = new \ilRadioOption($this->pl->txt('crs_prop_delete_mode_none'), $this->prop(CategoryOriginProperties::DELETE_MODE_NONE));
		$delete->addOption($opt);

		$opt = new \ilRadioOption(sprintf($this->pl->txt('cat_prop_delete_mode_inactive'), $this->pl->txt('com_prop_mark_deleted_text')), $this->prop(CategoryOriginProperties::DELETE_MODE_MARK));
		$delete->addOption($opt);

		$te = new \ilTextInputGUI($this->pl->txt('cat_prop_delete_mode_inactive_text'), $this->prop(CategoryOriginProperties::DELETE_MODE_MARK_TEXT));
		$te->setValue($this->origin->properties()->get(CategoryOriginProperties::DELETE_MODE_MARK_TEXT));
		$opt->addSubItem($te);

		$opt = new \ilRadioOption($this->pl->txt('crs_prop_delete_mode_delete'), $this->prop(CategoryOriginProperties::DELETE_MODE_DELETE));
		$delete->addOption($opt);

		$this->addItem($delete);

		parent::addPropertiesDelete();
	}
}