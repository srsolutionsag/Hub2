<?php

namespace srag\Plugins\Hub2\Origin\Config;

use srag\Plugins\Hub2\Exception\ConnectionFailedException;

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
	const PATH = 'file_path';
	const SERVER_HOST = 'server_host';
	const SERVER_PORT = 'server_port';
	const SERVER_USERNAME = 'server_username';
	const SERVER_PASSWORD = 'server_password';
	const SERVER_DATABASE = 'server_database';
	const SERVER_SEARCH_BASE = 'server_search_base';
	const ACTIVE_PERIOD = 'active_period';
	const LINKED_ORIGIN_ID = 'linked_origin_id';
	const CONNECTION_TYPE_PATH = 1;
	const CONNECTION_TYPE_SERVER = 2;
	const CONNECTION_TYPE_EXTERNAL = 3;
	const CONNECTION_TYPE_ILIAS_FILE = 4;
	// Prefix for keys that storing custom config values
	const CUSTOM_PREFIX = 'custom_';
	const ILIAS_FILE_REF_ID = "ilias_file_ref_id";


	/**
	 * Returns all the config data as associative array
	 *
	 * @return array
	 */
	public function getData(): array;


	/**
	 * @param array $data
	 *
	 * @return $this
	 */
	public function setData(array $data);


	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key);


	/**
	 * Get the value of a custom config entry or NULL if no config value is found.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function getCustom(string $key);


	/**
	 * @return int
	 *
	 * @throws ConnectionFailedException
	 */
	public function getConnectionType(): int;


	/**
	 * @return string
	 *
	 * @throws ConnectionFailedException
	 */
	public function getPath(): string;


	/**
	 * @return string
	 *
	 * @throws ConnectionFailedException
	 */
	public function getServerHost(): string;


	/**
	 * @return int
	 *
	 * @throws ConnectionFailedException
	 */
	public function getServerPort(): int;


	/**
	 * @return string
	 *
	 * @throws ConnectionFailedException
	 */
	public function getServerUsername(): string;


	/**
	 * @return string
	 *
	 * @throws ConnectionFailedException
	 */
	public function getServerPassword(): string;


	/**
	 * @return string
	 *
	 * @throws ConnectionFailedException
	 */
	public function getServerDatabase(): string;


	/**
	 * @return string
	 *
	 * @throws ConnectionFailedException
	 */
	public function getServerSearchBase(): string;


	/**
	 * @return int
	 *
	 * @throws ConnectionFailedException
	 */
	public function getIliasFileRefId(): int;


	/**
	 * @return string
	 *
	 * @throws ConnectionFailedException
	 */
	public function getIliasFilePath(): string;


	/**
	 * @return string
	 */
	public function getActivePeriod(): string;


	/**
	 * @return bool
	 */
	public function getCheckAmountData(): bool;


	/**
	 * @return int
	 */
	public function getCheckAmountDataPercentage(): int;


	/**
	 * @return bool
	 */
	public function useShortLink(): bool;


	/**
	 * @return bool
	 */
	public function useShortLinkForcedLogin(): bool;


	/**
	 * Get the ID of another origin which has been selected over the configuration GUI
	 *
	 * @return int
	 */
	public function getLinkedOriginId(): int;


	/**
	 * @return array
	 */
	public function getNotificationsSummary(): array;


	/**
	 * @return array
	 */
	public function getNotificationsErrors(): array;
}
