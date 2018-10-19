<?php

namespace srag\Plugins\Hub2\Origin\Config;

/**
 * Class OrgUnitOriginConfig
 *
 * @package srag\Plugins\Hub2\Origin\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitOriginConfig extends OriginConfig implements IOrgUnitOriginConfig {

	/**
	 * @var array
	 */
	protected $orgUnitConfig = [
		self::REF_ID_IF_NO_PARENT_ID => 0
	];


	/**
	 * @param array $data
	 */
	public function __construct(array $data = []) {
		parent::__construct(array_merge($this->orgUnitConfig, $data));
	}


	/**
	 * @inheritdoc
	 */
	public function getRefIdIfNoParentId(): int {
		return intval($this->data[self::REF_ID_IF_NO_PARENT_ID]);
	}
}
