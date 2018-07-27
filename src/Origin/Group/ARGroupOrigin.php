<?php

namespace SRAG\Plugins\Hub2\Origin\Group;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\GroupOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\GroupOriginProperties;

/**
 * Class ARGroupOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupOrigin extends AROrigin implements IGroupOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new GroupOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new GroupOriginProperties($data);
	}
}
