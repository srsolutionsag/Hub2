<?php

namespace SRAG\Plugins\Hub2\Config;

/**
 * Class HubConfig
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Config
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
	public function getShortLinkNoObject() {
		return ArConfig::getValueByKey(IHubConfig::SHORTLINK_NOT_FOUND);
	}


	/**
	 * @inheritdoc
	 */
	public function getShortLinkNoILIASId() {
		return ArConfig::getValueByKey(IHubConfig::SHORTLINK_NO_ILIAS_ID);
	}


	/**
	 * @inheritdoc
	 */
	public function getShortLinkNotActive() {
		return ArConfig::getValueByKey(IHubConfig::SHORTLINK_NOT_ACTIVE);
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

		return array_map(function ($id) {
			return (int)$id;
		}, $roles);
	}


	/**
	 * @inheritdoc
	 */
	public function get($key) {
		return ArConfig::getValueByKey($key);
	}
}