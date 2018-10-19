<?php

namespace srag\Plugins\Hub2\Origin\OrgUnit;

use srag\Plugins\Hub2\Origin\Config\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\Properties\IOrgUnitOriginProperties;

/**
 * Interface IOrgUnitOrigin
 *
 * @package srag\Plugins\Hub2\Origin\OrgUnit
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitOrigin extends IOrigin {

	/**
	 * @return IOrgUnitOriginConfig
	 */
	public function config(): IOrgUnitOriginConfig;


	/**
	 * @return IOrgUnitOriginProperties
	 */
	public function properties(): IOrgUnitOriginProperties;
}
