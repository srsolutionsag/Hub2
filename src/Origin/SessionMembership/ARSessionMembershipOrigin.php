<?php

namespace SRAG\Plugins\Hub2\Origin\SessionMembership;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\SessionMembershipOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\SessionMembershipOriginProperties;

/**
 * Class ARSessionMembershipOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionMembershipOrigin extends AROrigin implements ISessionMembershipOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new SessionMembershipOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new SessionMembershipOriginProperties($data);
	}
}
