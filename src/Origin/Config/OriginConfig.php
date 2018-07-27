<?php

namespace SRAG\Plugins\Hub2\Origin\Config;

/**
 * Class OriginConfig
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Origin\Config
 */
class OriginConfig implements IOriginConfig {

	/**
	 * @var array
	 */
	protected $data = [
		self::CHECK_AMOUNT => false,
		self::CHECK_AMOUNT_PERCENTAGE => 10,
		self::SHORT_LINK => false,
		self::SHORT_LINK_FORCE_LOGIN => false,
		self::NOTIFICATION_ERRORS => '',
		self::NOTIFICATION_SUMMARY => '',
		self::CONNECTION_TYPE => IOriginConfig::CONNECTION_TYPE_FILE,
		self::FILE_PATH => '',
		self::SERVER_HOST => '',
		self::SERVER_PORT => '',
		self::SERVER_USERNAME => '',
		self::SERVER_PASSWORD => '',
		self::SERVER_DATABASE => '',
		self::SERVER_SEARCH_BASE => '',
		self::ACTIVE_PERIOD => '',
		self::LINKED_ORIGIN_ID => 0,
	];


	/**
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->data = array_merge($this->data, $data);
	}


	/**
	 * @inheritdoc
	 */
	public function getServerHost() {
		return $this->data[self::SERVER_HOST];
	}


	/**
	 * @inheritdoc
	 */
	public function getServerPort() {
		return $this->data[self::SERVER_PORT];
	}


	/**
	 * @inheritdoc
	 */
	public function getServerUsername() {
		return $this->data[self::SERVER_USERNAME];
	}


	/**
	 * @inheritdoc
	 */
	public function getServerPassword() {
		return $this->data[self::SERVER_PASSWORD];
	}


	/**
	 * @inheritdoc
	 */
	public function getServerDatabase() {
		return $this->data[self::SERVER_DATABASE];
	}


	/**
	 * @inheritdoc
	 */
	public function getServerSearchBase() {
		return $this->data[self::SERVER_SEARCH_BASE];
	}


	/**
	 * @inheritdoc
	 */
	public function getFilePath() {
		return $this->data[self::FILE_PATH];
	}


	/**
	 * @inheritdoc
	 */
	public function getActivePeriod() {
		return $this->data[self::ACTIVE_PERIOD];
	}


	/**
	 * @inheritdoc
	 */
	public function getCheckAmountData() {
		return $this->data[self::CHECK_AMOUNT];
	}


	/**
	 * @inheritdoc
	 */
	public function getCheckAmountDataPercentage() {
		return $this->data[self::CHECK_AMOUNT_PERCENTAGE];
	}


	/**
	 * @inheritdoc
	 */
	public function useShortLink() {
		return $this->data[self::SHORT_LINK];
	}


	/**
	 * @inheritdoc
	 */
	public function useShortLinkForcedLogin() {
		return $this->data[self::SHORT_LINK_FORCE_LOGIN];
	}


	/**
	 * @inheritdoc
	 */
	public function getNotificationsErrors() {
		return explode(',', $this->data[self::NOTIFICATION_ERRORS]);
	}


	/**
	 * @inheritdoc
	 */
	public function getNotificationsSummary() {
		return explode(',', $this->data[self::NOTIFICATION_SUMMARY]);
	}


	/**
	 * @inheritdoc
	 */
	public function getConnectionType() {
		return $this->data[self::CONNECTION_TYPE];
	}


	/**
	 * @inheritdoc
	 */
	public function getLinkedOriginId() {
		return $this->data[self::LINKED_ORIGIN_ID];
	}


	/**
	 * @inheritdoc
	 */
	public function getCustom($key) {
		$key = self::CUSTOM_PREFIX . $key;

		return (isset($this->data[$key])) ? $this->data[$key] : NULL;
	}


	/**
	 * @inheritdoc
	 */
	public function getData() {
		return $this->data;
	}


	/**
	 * @inheritdoc
	 */
	public function setData(array $data) {
		$this->data = array_merge($this->data, $data);
	}
}
