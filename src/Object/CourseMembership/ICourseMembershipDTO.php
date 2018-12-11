<?php

namespace srag\Plugins\Hub2\Object\CourseMembership;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\IObjectRepository;

/**
 * Interface ICourseMembershipDTO
 *
 * @package srag\Plugins\Hub2\Object\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseMembershipDTO extends IDataTransferObject {

	const GLUE = IObjectRepository::GLUE;
}
