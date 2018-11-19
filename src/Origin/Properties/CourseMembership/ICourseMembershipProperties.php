<?php

namespace srag\Plugins\Hub2\Origin\Properties\CourseMembership;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitMembershipOriginProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseMembershipProperties extends IOriginProperties {

	const DELETE_MODE = 'delete_mode';
	const DELETE_MODE_NONE = 0;
	const DELETE_MODE_DELETE = 1;
}
