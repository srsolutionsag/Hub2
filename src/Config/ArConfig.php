<?php

namespace srag\Plugins\Hub2\Config;

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
	 * @var array
	 */
	protected static $field = [// TODO: Define fields here :)
	];


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
	public static function setCustomViewsActive(bool $active) {
		self::setBooleanValue(self::KEY_CUSTOM_VIEWS_ACTIVE, $active);
	}


	/**
	 * @inheritdoc
	 */
	public static function setGlobalHookActive(bool $active) {
		self::setBooleanValue(self::KEY_GLOBAL_HOCK_ACTIVE, $active);
	}


	/**
	 * @inheritdoc
	 */
	public static function isCustomViewsActive(): bool {
		return self::getBooleanValue(self::KEY_CUSTOM_VIEWS_ACTIVE, self::DEFAULT_CUSTOM_VIEWS_ACTIVE);
	}


	/**
	 * @inheritdoc
	 */
	public static function isGlobalHookActive(): bool {
		return self::getBooleanValue(self::KEY_GLOBAL_HOCK_ACTIVE, self::DEFAULT_GLOBAL_HOCK_ACTIVE);
	}


	/**
	 * @inheritdoc
	 */
	public static function setCustomViewsPath(string $path) {
		self::setStringValue(self::KEY_CUSTOM_VIEWS_PATH, $path);
	}


	/**
	 * @inheritdoc
	 */
	public static function setGlobalHookPath(string $path) {
		self::setStringValue(self::KEY_GLOBAL_HOCK_PATH, $path);
	}


	/**
	 * @inheritdoc
	 */
	public static function getCustomViewsPath(): string {
		return self::getStringValue(self::KEY_CUSTOM_VIEWS_PATH, self::DEFAULT_CUSTOM_VIEWS_PATH);
	}


	/**
	 * @inheritdoc
	 */
	public static function getGlobalHookPath(): string {
		return self::getStringValue(self::KEY_GLOBAL_HOCK_PATH, self::DEFAULT_GLOBAL_HOCK_PATH);
	}


	/**
	 * @inheritdoc
	 */
	public static function setCustomViewsClass(string $class) {
		self::setStringValue(self::KEY_CUSTOM_VIEWS_CLASS, $class);
	}


	/**
	 * @inheritdoc
	 */
	public static function setGlobalHookClass(string $class) {
		self::setStringValue(self::KEY_GLOBAL_HOCK_CLASS, $class);
	}


	/**
	 * @inheritdoc
	 */
	public static function getCustomViewsClass(): string {
		return self::getStringValue(self::KEY_CUSTOM_VIEWS_CLASS, self::DEFAULT_CUSTOM_VIEWS_CLASS);
	}


	/**
	 * @inheritdoc
	 */
	public static function getGlobalHookClass(): string {
		return self::getStringValue(self::KEY_GLOBAL_HOCK_CLASS, self::DEFAULT_GLOBAL_HOCK_CLASS);
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
