<?php

namespace SRAG\Plugins\Hub2\Config;

/**
 * Interface IHubConfig
 *
 * @package     SRAG\Plugins\Hub2\Config
 * @author      Stefan Wanzenried <sw@studer-raimann.ch>
 * @author      Fabian Schmid <fs@studer-raimann.ch>
 *
 * @deprecated  TODO: Use ArConfig and merge it with HubConfig (Static methods). Use for each key seperate methods with corresponding datatype and default value
 */
interface IHubConfig {

	const ORIGIN_IMPLEMENTATION_PATH = 'origin_implementation_path';
	const SHORTLINK_OBJECT_NOT_FOUND = 'shortlink_not_found';
	const SHORTLINK_OBJECT_NOT_ACCESSIBLE = 'shortlink_no_access';
	const SHORTLINK_SUCCESS = 'shortlink_success';
	const ADMINISTRATE_HUB_ROLE_IDS = 'administrate_hub_role_ids';
	const LOCK_ORIGINS_CONFIG = 'lock_origins_config';


	/**
	 * Get the path to the class files of origin implementations
	 *
	 * @return string
	 *
	 * @deprecated
	 */
	public function getOriginImplementationsPath();


	/**
	 * Get the message presented to the user if the ILIAS object was not found via shortlink.
	 *
	 * @return string
	 *
	 * @deprecated
	 */
	public function getShortLinkObjectNotFound();


	/**
	 * Get the message presented to the user if no ILIAS-ID is existing for the shortlink.
	 *
	 * @return string
	 *
	 * @deprecated
	 */
	public function getShortLinkObjectNotAccessible();


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getShortlinkSuccess();


	/**
	 * Get role IDs of ILIAS roles allowing to administrate hub, e.g. add and configure origins
	 *
	 * @return array
	 *
	 * @deprecated
	 */
	public function getAdministrationRoleIds();


	/**
	 * Should settings of origins be locked?
	 *
	 * @return bool
	 *
	 * @deprecated
	 */
	public function isOriginsConfigLocked();


	/**
	 * Get a config value by key.
	 *
	 * @param string $key
	 *
	 * @return string
	 *
	 * @deprecated
	 */
	public function get(string $key): string;
}
