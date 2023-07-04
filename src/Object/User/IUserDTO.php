<?php

namespace srag\Plugins\Hub2\Object\User;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Interface IUserDTO
 * @package srag\Plugins\Hub2\Object\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IUserDTO extends IDataTransferObject, IMetadataAwareDataTransferObject,
                           IMappingStrategyAwareDataTransferObject
{
    public const GENDER_MALE = 'm';
    public const GENDER_FEMALE = 'f';
    public const GENDER_NONE = " ";
    public const GENDER_NEUTRAL = "n";
    public const AUTH_MODE_ILIAS = 'local';
    public const AUTH_MODE_SHIB = 'shibboleth';
    public const AUTH_MODE_LDAP = 'ldap_1';
    public const AUTH_MODE_RADIUS = 'radius';
    public const AUTH_MODE_OIDC = 'oidc';
    public const USER_DEFAULT_ROLE = 4;
}
