<?php namespace SRAG\ILIAS\Plugins\Hub2\Origin\Config;

/**
 * Interface IOriginConfig
 *
 * Provides read-only access to the config data of an origin.
 * This config data is exposed to the implementation of an origin.
 *
 * @package SRAG\ILIAS\Plugins\Hub2\Origin
 */
interface IOriginConfig {

	const CONNECTION_TYPE_FILE = 1;
	const CONNECTION_TYPE_SERVER = 2;
	const CONNECTION_TYPE_EXTERNAL = 3;

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