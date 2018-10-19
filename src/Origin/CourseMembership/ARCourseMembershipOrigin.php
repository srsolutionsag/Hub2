<?php

namespace srag\Plugins\Hub2\Origin\CourseMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CourseMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CourseMembershipOriginProperties;

/**
 * Class ARCourseMembershipOrigin
 *
 * @package srag\Plugins\Hub2\Origin\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourseMembershipOrigin extends AROrigin implements ICourseMembershipOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new CourseMembershipOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new CourseMembershipOriginProperties($data);
	}
}
