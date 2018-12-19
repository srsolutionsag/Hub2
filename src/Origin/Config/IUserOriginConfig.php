<?php

namespace srag\Plugins\Hub2\Origin\Config;

/**
 * Interface IUserOriginConfig
 *
 * @package srag\Plugins\Hub2\Origin\Config
 */
interface IUserOriginConfig extends IOriginConfig {

	//	const SYNC_FIELD_NONE = 1;
	//	const SYNC_FIELD_EMAIL = 2;
	//	const SYNC_FIELD_EXT_ID = 3;

	const LOGIN_FIELD = 'ilias_login_field';
	const LOGIN_FIELD_SHORTENED_FIRST_LASTNAME = 1; // John Doe => j.doe
	const LOGIN_FIELD_EMAIL = 2;
	const LOGIN_FIELD_EXT_ACCOUNT = 3;
	const LOGIN_FIELD_EXT_ID = 4;
	const LOGIN_FIELD_FIRSTNAME_LASTNAME = 5; // John Doe => john.doe
	const LOGIN_FIELD_HUB_LOGIN = 6; // Login is picked from the login property on the UserDTO object
	/**
	 * @return int
	 */
	//	public function getSyncField();

	/**
	 * @return int
	 */
	public function getILIASLoginField();
}
