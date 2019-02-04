<?php

namespace srag\Plugins\Hub2\Object\User;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Interface IUserDTO
 *
 * @package srag\Plugins\Hub2\Object\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IUserDTO extends IDataTransferObject, IMetadataAwareDataTransferObject, IMappingStrategyAwareDataTransferObject {

	const GENDER_MALE = 'm';
	const GENDER_FEMALE = 'f';
	const GENDER_NONE = " ";
	const AUTH_MODE_ILIAS = 'local';
	const AUTH_MODE_SHIB = 'shibboleth';
	const AUTH_MODE_LDAP = 'ldap_1';
	const AUTH_MODE_RADIUS = 'radius';
}
