<?php

namespace SRAG\Plugins\Hub2\Config;

/**
 * Class HubConfig
 *
 * @package SRAG\Plugins\Hub2\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class HubConfig implements IHubConfig {

	/**
	 * @inheritdoc
	 */
	public function getOriginImplementationsPath() {
		$path = ArConfig::getValueByKey(IHubConfig::ORIGIN_IMPLEMENTATION_PATH);

		return ($path) ? $path : dirname(dirname(__DIR__)) . '/origins/';
	}


	/**
	 * @inheritdoc
	 */
	public function getShortLinkObjectNotFound() {
		return ArConfig::getValueByKey(IHubConfig::SHORTLINK_OBJECT_NOT_FOUND);
	}


	/**
	 * @inheritdoc
	 */
	public function getShortLinkObjectNotAccessible() {
		return ArConfig::getValueByKey(IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE);
	}


	/**
	 * @inheritDoc
	 */
	public function getShortlinkSuccess() {
		return ArConfig::getValueByKey(IHubConfig::SHORTLINK_SUCCESS);
	}


	/**
	 * @inheritdoc
	 */
	public function isOriginsConfigLocked() {
		return (bool)ArConfig::getValueByKey(IHubConfig::LOCK_ORIGINS_CONFIG);
	}


	/**
	 * @inheritdoc
	 */
	public function getAdministrationRoleIds() {
		$roles = ArConfig::getValueByKey(IHubConfig::ADMINISTRATE_HUB_ROLE_IDS);
		$roles = explode(',', $roles);

		return array_map(
			function ($id) {
				return (int)$id;
			}, $roles
		);
	}


	/**
	 * @inheritdoc
	 */
	public function get($key) {
		return ArConfig::getValueByKey($key);
	}
}
