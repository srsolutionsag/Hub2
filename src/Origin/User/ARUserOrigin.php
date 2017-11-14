<?php

namespace SRAG\Plugins\Hub2\Origin\User;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\UserOriginProperties;

/**
 * Class ARUserOrigin
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Origin
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