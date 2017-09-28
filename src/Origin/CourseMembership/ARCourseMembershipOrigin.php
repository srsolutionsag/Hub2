<?php

namespace SRAG\Hub2\Origin\CourseMembership;

use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Origin\Config\CourseMembershipOriginConfig;
use SRAG\Hub2\Origin\Properties\CourseMembershipOriginProperties;

/**
 * Class ARCourseMembershipOrigin
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
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