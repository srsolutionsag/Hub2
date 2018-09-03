<?php

namespace SRAG\Plugins\Hub2\UI;

use ilTextInputGUI;
use SRAG\Plugins\Hub2\Origin\Config\IOrgUnitOriginConfig;
use SRAG\Plugins\Hub2\Origin\OrgUnit\AROrgUnitOrigin;

/**
 * Class OrgUnitOriginConfigFormGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var AROrgUnitOrigin
	 */
	protected $origin;


	/**
	 * @inheritdoc
	 */
	protected function addSyncConfig() {
		parent::addSyncConfig();

		$ref_id_if_no_parent_id = new ilTextInputGUI(self::plugin()->translate("orgunit_ref_id_if_no_parent_id"), $this->conf(IOrgUnitOriginConfig::REF_ID_IF_NO_PARENT_ID));
		$ref_id_if_no_parent_id->setInfo(self::plugin()->translate("orgunit_ref_id_if_no_parent_id_info"));
		$ref_id_if_no_parent_id->setValue($this->origin->config()->getRefIdIfNoParentId());
		$this->addItem($ref_id_if_no_parent_id);
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
