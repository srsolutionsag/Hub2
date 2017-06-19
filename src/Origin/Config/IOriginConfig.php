<?php namespace SRAG\Hub2\Origin\Config;

/**
 * Interface IOriginConfig
 *
 * @package SRAG\Hub2\Origin\Config
 */
interface IOriginConfig {

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
	 * Get the value of a custom config entry or NULL if no config value is found.
	 *
	 * @param string $key
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
	 * @return $this
	 */
	public function setData(array $data);
}