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
	const VIEW_MODE_SESSIONS = 0; //\ilContainer::VIEW_SESSIONS;
	const VIEW_MODE_OBJECTIVES = 1; //\ilContainer::VIEW_OBJECTIVE;
	const VIEW_MODE_TIMING = 2; //\ilContainer::VIEW_TIMING;
	const VIEW_MODE_SIMPLE = 4; //\ilContainer::VIEW_SIMPLE;
	const VIEW_MODE_BY_TYPE = 5; //\ilContainer::VIEW_BY_TYPE;
	const VIEW_MODE_INHERIT = 6; //\ilContainer::VIEW_INHERIT;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	const ACTIVATION_OFFLINE = 0;
	const ACTIVATION_UNLIMITED = 1;
	const ACTIVATION_LIMITED = 2;
	const VIEW_DEFAULT = self::VIEW_MODE_BY_TYPE;
	const SORT_TITLE = 0;//\ilContainer::SORT_TITLE;
	const SORT_MANUAL = 1;//\ilContainer::SORT_MANUAL;
	const SORT_ACTIVATION = 2;//\ilContainer::SORT_ACTIVATION;
	const SORT_INHERIT = 3;//\ilContainer::SORT_INHERIT;
	const SORT_CREATION = 4;//\ilContainer::SORT_CREATION;
	const SORT_DIRECTION_ASC = 0;//\ilContainer::SORT_DIRECTION_ASC;
	const SORT_DIRECTION_DESC = 1;//\ilContainer::SORT_DIRECTION_DESC;
}
