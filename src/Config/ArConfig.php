<?php

namespace SRAG\Plugins\Hub2\Config;

use hub2RemoveDataConfirm;
use ilHub2Plugin;
use srag\ActiveRecordConfig\ActiveRecordConfig;

/**
 * Class ArConfig
 *
 * @package SRAG\Plugins\Hub2\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ArConfig extends ActiveRecordConfig {

	const TABLE_NAME = 'sr_hub2_config_n';
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @param string      $key
	 * @param string|null $default_value
	 *
	 * @return string
	 *
	 * @deprecated Use ActiveRecordConfig directly with getStringValue or similar method
	 */
	public static function getValueByKey(string $key, $default_value = NULL) {
		return self::getStringValue($key, $default_value);
	}


	/**
	 * @param string $name
	 * @param string $value
	 *
	 * @deprecated Use ActiveRecordConfig directly with setStringValue or similar method
	 *
	 */
	public static function setValueByKey(string $name, $value) {
		self::setStringValue($name, (string)$value);
	}


	/**
	 * @return bool|null
	 */
	public static function getUninstallRemovesData()/*: ?bool*/ {
		return self::getXValue(hub2RemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, hub2RemoveDataConfirm::DEFAULT_UNINSTALL_REMOVES_DATA);
	}


	/**
	 * @param bool $uninstall_removes_data
	 */
	public static function setUninstallRemovesData(bool $uninstall_removes_data)/*: void*/ {
		self::setBooleanValue(hub2RemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, $uninstall_removes_data);
	}


	/**
	 *
	 */
	public static function removeUninstallRemovesData()/*: void*/ {
		self::removeName(hub2RemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA);
	}
}
