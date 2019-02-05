<?php

namespace srag\Plugins\Hub2\Object\Session;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;

/**
 * Interface ISessionDTO
 *
 * @package srag\Plugins\Hub2\Object\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISessionDTO extends IDataTransferObject, IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject {

	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	const REGISTRATION_TYPE_NONE = 0;
	const REGISTRATION_TYPE_DIRECT = 1;
	const REGISTRATION_TYPE_REQUEST = 3;
}
