<?php

namespace SRAG\Plugins\Hub2\Origin\CourseMembership;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\CourseMembershipOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\CourseMembershipOriginProperties;

/**
 * Class ARCourseMembershipOrigin
 *
 * @package SRAG\Plugins\Hub2\Origin\CourseMembership
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
