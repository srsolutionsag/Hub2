<?php

namespace SRAG\Plugins\Hub2\Config;

use ilHub2Plugin;
use srag\ActiveRecordConfig\ActiveRecordConfig;
use srag\DIC\DICTrait;

/**
 * Class ArConfig
 *
 * @package SRAG\Plugins\Hub2\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ArConfig extends ActiveRecordConfig {

	use DICTrait;
	const TABLE_NAME = 'sr_hub2_config_n';
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const KEY_UNINSTALL_REMOVE_HUB2_DATA = "uninstall_remove_hub2_data";
	const DEFAULT_UNINSTALL_REMOVE_HUB2_DATA = NULL;


	/**
	 * @param string      $key
	 * @param string|null $default_value
	 *
	 * @return string
	 */
	public static function getValueByKey(string $key, $default_value = NULL) {
		return self::getStringValue($key, $default_value);
	}


	/**
	 * @param string $name
	 * @param string $value
	 */
	public static function setValueByKey(string $name, $value) {
		self::setStringValue($name, $value);
	}


	/**
	 * @return bool|null
	 */
	public static function getUninstallRemoveHub2Data() {
		return self::getXValue(self::KEY_UNINSTALL_REMOVE_HUB2_DATA, self::DEFAULT_UNINSTALL_REMOVE_HUB2_DATA);
	}


	/**
	 * @param bool|null $uninstall_remove_hub_2_data
	 */
	public static function setUninstallRemoveHub2Data($uninstall_remove_hub_2_data) {
		self::setXValue(self::KEY_UNINSTALL_REMOVE_HUB2_DATA, $uninstall_remove_hub_2_data);
	}


	/**
	 *
	 */
	public static function deleteUninstallRemoveHub2Data() {
		self::deleteName(self::KEY_UNINSTALL_REMOVE_HUB2_DATA);
	}
}
