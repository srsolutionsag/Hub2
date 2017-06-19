<?php namespace SRAG\Hub2\Origin\Config;

/**
 * Class OriginConfig
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Config
 */
class OriginConfig implements IOriginConfig {

	/**
	 * @var array
	 */
	protected $data = [
		'check_amount' => false,
		'check_amount_percentage' => 10,
		'shortlink' => false,
		'shortlink_force_login' => false,
		'notification_errors' => [],
		'notification_summary' => [],
		'connection_type' => IOriginConfig::CONNECTION_TYPE_FILE,
		'file_path' => '',
		'server_host' => '',
		'server_port' => '',
		'server_username' => '',
		'server_password' => '',
		'server_database' => '',
		'server_search_base' => '',
		'active_period' => '',
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
		return $this->data['server_host'];
	}

	/**
	 * @inheritdoc
	 */
	public function getServerPort() {
		return $this->data['server_port'];
	}

	/**
	 * @inheritdoc
	 */
	public function getServerUsername() {
		return $this->data['server_username'];
	}

	/**
	 * @inheritdoc
	 */
	public function getServerPassword() {
		return $this->data['server_password'];
	}

	/**
	 * @inheritdoc
	 */
	public function getServerDatabase() {
		return $this->data['server_database'];
	}

	/**
	 * @inheritdoc
	 */
	public function getServerSearchBase() {
		return $this->data['server_search_base'];
	}

	/**
	 * @inheritdoc
	 */
	public function getFilePath() {
		return $this->data['file_path'];
	}

	/**
	 * @inheritdoc
	 */
	public function getActivePeriod() {
		return $this->data['active_period'];
	}

	/**
	 * @inheritdoc
	 */
	public function getCheckAmountData() {
		return $this->data['check_amount'];
	}

	/**
	 * @inheritdoc
	 */
	public function getCheckAmountDataPercentage() {
		return $this->data['check_amount_percentage'];
	}

	/**
	 * @inheritdoc
	 */
	public function useShortLink() {
		return $this->data['shortlink'];
	}

	/**
	 * @inheritdoc
	 */
	public function useShortLinkForcedLogin() {
		return $this->data['shortlink_force_login'];
	}

	/**
	 * @inheritdoc
	 */
	public function getNotificationsErrors() {
		return $this->data['notification_errors'];
	}

	/**
	 * @inheritdoc
	 */
	public function getNotificationsSummary() {
		return $this->data['notification_summary'];
	}

	/**
	 * @inheritdoc
	 */
	public function getConnectionType() {
		return $this->data['connection_type'];
	}

	/**
	 * @inheritdoc
	 */
	public function getCustom($key) {
		$key = self::CUSTOM_PREFIX . $key;
		return (isset($this->data[$key])) ? $this->data[$key] : null;
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