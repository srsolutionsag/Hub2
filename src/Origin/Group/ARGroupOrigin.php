<?php

namespace srag\Plugins\Hub2\Origin\Group;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\GroupOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\GroupOriginProperties;

/**
 * Class ARGroupOrigin
 *
 * @package srag\Plugins\Hub2\Origin\Group
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
