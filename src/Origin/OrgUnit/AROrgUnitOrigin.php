<?php

namespace SRAG\Plugins\Hub2\Origin\OrgUnit;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\OrgUnitOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\OrgUnitOriginProperties;

/**
 * Class AROrgUnitOrigin
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class AROrgUnitOrigin extends AROrigin implements IOrgUnitOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data): OrgUnitOriginConfig {
		return new OrgUnitOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data): OrgUnitOriginProperties {
		return new OrgUnitOriginProperties($data);
	}
}
