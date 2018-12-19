<?php

namespace srag\Plugins\Hub2\Config;

/**
 * Interface IArConfig
 *
 * @package     srag\Plugins\Hub2\Config
 *
 * @author      Stefan Wanzenried <sw@studer-raimann.ch>
 * @author      Fabian Schmid <fs@studer-raimann.ch>
 */
interface IArConfig {

	const KEY_ORIGIN_IMPLEMENTATION_PATH = 'origin_implementation_path';
	const KEY_SHORTLINK_OBJECT_NOT_FOUND = 'shortlink_not_found';
	const KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE = 'shortlink_no_access';
	const KEY_SHORTLINK_SUCCESS = 'shortlink_success';
	const KEY_ADMINISTRATE_HUB_ROLE_IDS = 'administrate_hub_role_ids';
	const KEY_LOCK_ORIGINS_CONFIG = 'lock_origins_config';
	/**
	 * @var string
	 *
	 * TODO: Use self::DEFAULT_ORIGIN_IMPLEMENTATION_PATH but there you can not use function like dirname! And not use realpath if you think to use it!
	 */
	const DEFAULT_ORIGIN_IMPLEMENTATION_PATH = '';
	const DEFAULT_SHORTLINK_OBJECT_NOT_FOUND = '';
	const DEFAULT_SHORTLINK_OBJECT_NOT_ACCESSIBLE = '';
	const DEFAULT_SHORTLINK_SUCCESS = '';
	const DEFAULT_ADMINISTRATE_HUB_ROLE_IDS = [];
	const DEFAULT_LOCK_ORIGINS_CONFIG = '';


	/**
	 * Get the path to the class files of origin implementations
	 *
	 * @return string
	 */
	public static function getOriginImplementationsPath(): string;


	/**
	 * @param string $origin_implementations_path
	 */
	public static function setOriginImplementationsPath(string $origin_implementations_path)/*: void*/
	;


	/**
	 * Get the message presented to the user if the ILIAS object was not found via shortlink.
	 *
	 * @return string
	 */
	public static function getShortLinkObjectNotFound(): string;


	/**
	 * @param string $shortlink_object_not_found
	 */
	public static function setShortLinkObjectNotFound(string $shortlink_object_not_found)/*: void*/
	;


	/**
	 * Get the message presented to the user if no ILIAS-ID is existing for the shortlink.
	 *
	 * @return string
	 */
	public static function getShortLinkObjectNotAccessible(): string;


	/**
	 * @param string $shortlink_object_not_accessible
	 */
	public static function setShortLinkObjectNotAccessible(string $shortlink_object_not_accessible)/*: void*/
	;


	/**
	 * @return string
	 */
	public static function getShortlinkSuccess(): string;


	/**
	 * @param string shortlink_success
	 */
	public static function setShortlinkSuccess(string $shortlink_success)/*: void*/
	;


	/**
	 * Get role IDs of ILIAS roles allowing to administrate hub, e.g. add and configure origins
	 *
	 * @return array
	 */
	public static function getAdministrationRoleIds(): array;


	/**
	 * @param string $administration_role_ids
	 */
	public static function setAdministrationRoleIds(array $administration_role_ids)/*: void*/
	;


	/**
	 * Should settings of origins be locked?
	 *
	 * @return bool
	 */
	public static function isOriginsConfigLocked(): bool;


	/**
	 * @param string $origins_config_locked
	 */
	public static function setOriginsConfigLocked(bool $origins_config_locked)/*: void*/
	;


	/**
	 * @return bool|null
	 */
	public static function getUninstallRemovesData()/*: ?bool*/
	;


	/**
	 * @param bool $uninstall_removes_data
	 */
	public static function setUninstallRemovesData(bool $uninstall_removes_data)/*: void*/
	;


	/**
	 *
	 */
	public static function removeUninstallRemovesData()/*: void*/
	;
}
