<?php namespace SRAG\Hub2\Origin\Config;

/**
 * Class UserOriginConfig
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Config
 */
class UserOriginConfig extends OriginConfig implements IUserOriginConfig {

	/**
	 * @var array
	 */
	protected $user_data = [
//		'sync_field' => IUserOriginConfig::SYNC_FIELD_NONE,
		self::LOGIN_FIELD => IUserOriginConfig::LOGIN_FIELD_SHORTENED_FIRST_LASTNAME,
	];

	public function __construct(array $data) {
		parent::__construct(array_merge($this->user_data, $data));
	}

//	/**
//	 * @inheritdoc
//	 */
//	public function getSyncField() {
//		return $this->data['sync_field'];
//	}

	/**
	 * @inheritdoc
	 */
	public function getILIASLoginField() {
		return $this->data[self::LOGIN_FIELD];
	}
}