<?php

namespace SRAG\Plugins\Hub2\Origin\OrgUnit;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\IOrgUnitOriginConfig;
use SRAG\Plugins\Hub2\Origin\Config\OrgUnitOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\IOrgUnitOriginProperties;
use SRAG\Plugins\Hub2\Origin\Properties\OrgUnitOriginProperties;

/**
 * Class AROrgUnitOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\OrgUnit
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
