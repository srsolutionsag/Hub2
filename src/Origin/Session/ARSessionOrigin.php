<?php

namespace SRAG\Plugins\Hub2\Origin\Session;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\SessionOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\SessionOriginProperties;

/**
 * Class ARSessionOrigin
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionOrigin extends AROrigin implements ISessionOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new SessionOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new SessionOriginProperties($data);
	}
}