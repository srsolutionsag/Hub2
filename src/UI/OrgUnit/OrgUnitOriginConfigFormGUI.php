<?php

namespace srag\Plugins\Hub2\UI\OrgUnit;

use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\OrgUnit\AROrgUnitOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class OrgUnitOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI\OrgUnit
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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

		$ref_id_if_no_parent_id = new ilTextInputGUI(self::plugin()
			->translate("orgunit_ref_id_if_no_parent_id"), $this->conf(IOrgUnitOriginConfig::REF_ID_IF_NO_PARENT_ID));
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
