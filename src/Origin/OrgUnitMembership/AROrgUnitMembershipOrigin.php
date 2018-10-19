<?php

namespace srag\Plugins\Hub2\Origin\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\IOrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Config\OrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOrgUnitMembershipOriginProperties;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitMembershipOriginProperties;

/**
 * Class AROrgUnitMembershipOrigin
 *
 * @package srag\Plugins\Hub2\Origin\OrgUnitMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class AROrgUnitMembershipOrigin extends AROrigin implements IOrgUnitMembershipOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data): IOrgUnitMembershipOriginConfig {
		return new OrgUnitMembershipOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data): IOrgUnitMembershipOriginProperties {
		return new OrgUnitMembershipOriginProperties($data);
	}


	/**
	 * @inheritdoc
	 */
	public function config(): IOrgUnitMembershipOriginConfig {
		return parent::config();
	}


	/**
	 * @inheritdoc
	 */
	public function properties(): IOrgUnitMembershipOriginProperties {
		return parent::properties();
	}
}
