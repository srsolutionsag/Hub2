<?php

namespace srag\Plugins\Hub2\Origin\Config\User;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Interface IUserOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\User
 */
interface IUserOriginConfig extends IOriginConfig
{
    //	const SYNC_FIELD_NONE = 1;
    //	const SYNC_FIELD_EMAIL = 2;
    //	const SYNC_FIELD_EXT_ID = 3;

    public const LOGIN_FIELD = 'ilias_login_field';
    public const LOGIN_FIELD_SHORTENED_FIRST_LASTNAME = 1; // John Doe => j.doe
    public const LOGIN_FIELD_EMAIL = 2;
    public const LOGIN_FIELD_EXT_ACCOUNT = 3;
    public const LOGIN_FIELD_EXT_ID = 4;
    public const LOGIN_FIELD_FIRSTNAME_LASTNAME = 5; // John Doe => john.doe
    public const LOGIN_FIELD_HUB_LOGIN = 6; // Login is picked from the login property on the UserDTO object
    public const LOGIN_KEEP_CASE = 'login_keep_case';

    //	public function getSyncField():int;
    public function getILIASLoginField() : int;

    public function isKeepCase() : bool;

    public static function getAvailableLoginFields() : array;
}
