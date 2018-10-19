<?php

namespace srag\Plugins\Hub2\Origin\Config;

/**
 * Interface IOriginConfig
 *
 * @package srag\Plugins\Hub2\Origin\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginConfig {

	const CHECK_AMOUNT = 'check_amount';
	const CHECK_AMOUNT_PERCENTAGE = 'check_amount_percentage';
	const SHORT_LINK = 'shortlink';
	const SHORT_LINK_FORCE_LOGIN = 'shortlink_force_login';
	const NOTIFICATION_ERRORS = 'notification_errors';
	const NOTIFICATION_SUMMARY = 'notification_summary';
	const CONNECTION_TYPE = 'connection_type';
	const FILE_PATH = 'file_path';
	const SERVER_HOST = 'server_host';
	const SERVER_PORT = 'server_port';
	const SERVER_USERNAME = 'server_username';
	const SERVER_PASSWORD = 'server_password';
	const SERVER_DATABASE = 'server_database';
	const SERVER_SEARCH_BASE = 'server_search_base';
	const ACTIVE_PERIOD = 'active_period';
	const LINKED_ORIGIN_ID = 'linked_origin_id';
	const CONNECTION_TYPE_FILE = 1;
	const CONNECTION_TYPE_SERVER = 2;
	const CONNECTION_TYPE_EXTERNAL = 3;
	// Prefix for keys that storing custom config values
	const CUSTOM_PREFIX = 'custom_';


	/**
	 * @return bool
	 */
	public function getCheckAmountData();


	/**
	 * @return int
	 */
	public function getCheckAmountDataPercentage();


	/**
	 * @return bool
	 */
	public function useShortLink();


	/**
	 * @return bool
	 */
	public function useShortLinkForcedLogin();


	/**
	 * @return array
	 */
	public function getNotificationsErrors();


	/**
	 * @return array
	 */
	public function getNotificationsSummary();


	/**
	 * @return int
	 */
	public function getConnectionType();


	/**
	 * @return string
	 */
	public function getServerHost();


	/**
	 * @return int
	 */
	public function getServerPort();


	/**
	 * @return string
	 */
	public function getServerUsername();


	/**
	 * @return string
	 */
	public function getServerPassword();


	/**
	 * @return string
	 */
	public function getServerDatabase();


	/**
	 * @return string
	 */
	public function getServerSearchBase();


	/**
	 * @return string
	 */
	public function getFilePath();


	/**
	 * @return string
	 */
	public function getActivePeriod();


	/**
	 * Get the ID of another origin which has been selected over the configuration GUI
	 *
	 * @return int
	 */
	public function getLinkedOriginId();


	/**
	 * Get the value of a custom config entry or NULL if no config value is found.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function getCustom($key);


	/**
	 * Returns all the config data as associative array
	 *
	 * @return array
	 */
	public function getData();


	/**
	 * @param array $data
	 *
	 * @return $this
	 */
	public function setData(array $data);
}
