<?php

namespace SRAG\Hub2\Origin\Session;

use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Origin\Config\SessionOriginConfig;
use SRAG\Hub2\Origin\Properties\SessionOriginProperties;

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