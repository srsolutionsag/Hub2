<?php

namespace srag\Plugins\Hub2\Origin\User;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\User\UserOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\User\UserProperties;

/**
 * Class ARUserOrigin
 *
 * @package srag\Plugins\Hub2\Origin\User
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
		return new UserProperties($data);
	}
}
