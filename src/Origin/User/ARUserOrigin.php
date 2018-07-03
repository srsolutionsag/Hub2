<?php

namespace SRAG\Plugins\Hub2\Origin\User;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\UserOriginProperties;

/**
 * Class ARUserOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARUserOrigin extends AROrigin implements IUserOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new UserOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new UserOriginProperties($data);
	}
}
