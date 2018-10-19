<?php

namespace srag\Plugins\Hub2\Origin\OrgUnit;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\Config\OrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOrgUnitOriginProperties;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitOriginProperties;

/**
 * Class AROrgUnitOrigin
 *
 * @package srag\Plugins\Hub2\Origin\OrgUnit
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class AROrgUnitOrigin extends AROrigin implements IOrgUnitOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data): IOrgUnitOriginConfig {
		return new OrgUnitOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data): IOrgUnitOriginProperties {
		return new OrgUnitOriginProperties($data);
	}


	/**
	 * @inheritdoc
	 */
	public function config(): IOrgUnitOriginConfig {
		return parent::config();
	}


	/**
	 * @inheritdoc
	 */
	public function properties(): IOrgUnitOriginProperties {
		return parent::properties();
	}
}
