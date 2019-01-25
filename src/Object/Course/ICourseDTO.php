<?php

namespace srag\Plugins\Hub2\Object\Course;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAndMetadataAwareDataTransferObject;

/**
 * Interface ICourseDTO
 *
 * @package srag\Plugins\Hub2\Object\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseDTO extends IDataTransferObject, ITaxonomyAndMetadataAwareDataTransferObject, IMappingStrategyAwareDataTransferObject {

	// @see ilCourseConstants
	const SUBSCRIPTION_TYPE_DEACTIVATED = 1;
	const SUBSCRIPTION_TYPE_REQUEST_MEMBERSHIP = 2;
	const SUBSCRIPTION_TYPE_DIRECTLY = 3;
	const SUBSCRIPTION_TYPE_PASSWORD = 4;
	const VIEW_MODE_SESSIONS = 0;
	const VIEW_MODE_OBJECTIVES = 1;
	const VIEW_MODE_TIMING = 2;
	const VIEW_MODE_SIMPLE = 4;
	const VIEW_MODE_BY_TYPE = 5;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	const ACTIVATION_OFFLINE = 0;
	const ACTIVATION_UNLIMITED = 1;
	const ACTIVATION_LIMITED = 2;
}
