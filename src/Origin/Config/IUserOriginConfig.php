<?php namespace SRAG\Hub2\Origin\Config;

/**
 * Interface IUserOriginConfig
 * @package SRAG\Hub2\Origin\Config
 */
interface IUserOriginConfig extends IOriginConfig {

	const SYNC_FIELD_NONE = 1;
	const SYNC_FIELD_EMAIL = 2;
	const SYNC_FIELD_EXT_ID = 3;

	const LOGIN_FIELD_STANDARD = 1;
	const LOGIN_FIELD_EMAIL = 2;
	const LOGIN_FIELD_EXT_ACCOUNT = 3;
	const LOGIN_FIELD_EXT_ID = 4;
	const LOGIN_FIELD_FIRSTNAME_LASTNAME = 5;
	const LOGIN_FIELD_HUB_LOGIN = 6;

	/**
	 * @return int
	 */
	public function getSyncField();

	/**
	 * @return int
	 */
	public function getILIASLoginField();
}