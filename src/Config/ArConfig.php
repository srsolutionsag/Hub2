<?php

namespace srag\Plugins\Hub2\Config;

use hub2RemoveDataConfirm;
use ilHub2Plugin;
use srag\ActiveRecordConfig\ActiveRecordConfig;

/**
 * Class ArConfig
 *
 * @package srag\Plugins\Hub2\Config
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ArConfig extends ActiveRecordConfig implements IArConfig {

	const TABLE_NAME = 'sr_hub2_config_n';
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritdoc
	 */
	public static function getOriginImplementationsPath(): string {
		return self::getStringValue(self::KEY_ORIGIN_IMPLEMENTATION_PATH, dirname(dirname(__DIR__))
			. '/origins/'); // TODO: Use self::DEFAULT_ORIGIN_IMPLEMENTATION_PATH but there you can not use function like dirname! And not use realpath if you think to use it!
	}


	/**
	 * @inheritdoc
	 */
	public static function setOriginImplementationsPath(string $origin_implementations_path)/*: void*/ {
		self::setStringValue(self::KEY_ORIGIN_IMPLEMENTATION_PATH, $origin_implementations_path);
	}


	/**
	 * @inheritdoc
	 */
	public static function getShortLinkObjectNotFound(): string {
		return self::getStringValue(self::KEY_SHORTLINK_OBJECT_NOT_FOUND, self::DEFAULT_SHORTLINK_OBJECT_NOT_FOUND);
	}


	/**
	 * @inheritdoc
	 */
	public static function setShortLinkObjectNotFound(string $shortlink_object_not_found)/*: void*/ {
		self::setStringValue(self::KEY_SHORTLINK_OBJECT_NOT_FOUND, $shortlink_object_not_found);
	}


	/**
	 * @inheritdoc
	 */
	public static function getShortLinkObjectNotAccessible(): string {
		return self::getStringValue(self::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE, self::DEFAULT_SHORTLINK_OBJECT_NOT_ACCESSIBLE);
	}


	/**
	 * @inheritdoc
	 */
	public static function setShortLinkObjectNotAccessible(string $shortlink_object_not_accessible)/*: void*/ {
		self::setStringValue(self::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE, $shortlink_object_not_accessible);
	}


	/**
	 * @inheritdoc
	 */
	public static function getShortlinkSuccess(): string {
		return self::getStringValue(self::KEY_SHORTLINK_SUCCESS, self::DEFAULT_SHORTLINK_SUCCESS);
	}


	/**
	 * @inheritdoc
	 */
	public static function setShortlinkSuccess(string $shortlink_success)/*: void*/ {
		self::setStringValue(self::KEY_SHORTLINK_SUCCESS, $shortlink_success);
	}


	/**
	 * @inheritdoc
	 */
	public static function getAdministrationRoleIds(): array {
		return self::getJsonValue(self::KEY_ADMINISTRATE_HUB_ROLE_IDS, true, self::DEFAULT_ADMINISTRATE_HUB_ROLE_IDS);
	}


	/**
	 * @inheritdoc
	 */
	public static function setAdministrationRoleIds(array $administration_role_ids)/*: void*/ {
		self::setJsonValue(self::KEY_ADMINISTRATE_HUB_ROLE_IDS, $administration_role_ids);
	}


	/**
	 * @inheritdoc
	 */
	public static function isOriginsConfigLocked(): bool {
		return self::getBooleanValue(self::KEY_LOCK_ORIGINS_CONFIG, self::DEFAULT_LOCK_ORIGINS_CONFIG);
	}


	/**
	 * @inheritdoc
	 */
	public static function setOriginsConfigLocked(bool $origins_config_locked)/*: void*/ {
		self::setBooleanValue(self::KEY_LOCK_ORIGINS_CONFIG, $origins_config_locked);
	}


	/**
	 * @inheritdoc
	 */
	public static function getUninstallRemovesData()/*: ?bool*/ {
		return self::getXValue(hub2RemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, hub2RemoveDataConfirm::DEFAULT_UNINSTALL_REMOVES_DATA);
	}


	/**
	 * @inheritdoc
	 */
	public static function setUninstallRemovesData(bool $uninstall_removes_data)/*: void*/ {
		self::setBooleanValue(hub2RemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, $uninstall_removes_data);
	}


	/**
	 * @inheritdoc
	 */
	public static function removeUninstallRemovesData()/*: void*/ {
		self::removeName(hub2RemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA);
	}


	/**
	 * @param string      $key
	 * @param string|null $default_value
	 *
	 * @return string
	 *
	 * @deprecated
	 */
	public static function getValueByKey(string $key, /*?*/
		string $default_value = NULL): string {
		return self::getStringValue($key, $default_value);
	}


	/**
	 * @param string $name
	 * @param string $value
	 *
	 * @deprecated
	 */
	public static function setValueByKey(string $name, string $value)/*: void*/ {
		self::setStringValue($name, $value);
	}
}
