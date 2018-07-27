<?php

namespace SRAG\Plugins\Hub2\Origin\GroupMembership;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\GroupMembershipOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\GroupMembershipOriginProperties;

/**
 * Class ARGroupMembershipOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupMembershipOrigin extends AROrigin implements IGroupMembershipOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new GroupMembershipOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new GroupMembershipOriginProperties($data);
	}
}
