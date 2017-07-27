<?php namespace SRAG\Hub2\Config;

/**
 * Class HubConfig
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Config
 */
class HubConfig implements IHubConfig {

	/**
	 * @inheritdoc
	 */
	public function getOriginImplementationsPath() {
		$path = ARConfig::getValueByKey(IHubConfig::ORIGIN_IMPLEMENTATION_PATH);
		return ($path) ? $path : dirname(dirname(__DIR__)) . '/origins/';
	}

	/**
	 * @inheritdoc
	 */
	public function getShortLinkNoObject() {
		return ARConfig::getValueByKey(IHubConfig::SHORTLINK_NOT_FOUND);
	}

	/**
	 * @inheritdoc
	 */
	public function getShortLinkNoILIASId() {
		return ARConfig::getValueByKey(IHubConfig::SHORTLINK_NO_ILIAS_ID);
	}

	/**
	 * @inheritdoc
	 */
	public function getShortLinkNotActive() {
		return ARConfig::getValueByKey(IHubConfig::SHORTLINK_NOT_ACTIVE);
	}

	/**
	 * @inheritdoc
	 */
	public function getAdministrationRoleIds() {
		$roles = ARConfig::getValueByKey(IHubConfig::ADMINISTRATE_HUB_ROLE_IDS);
		$roles = explode(',', $roles);
		return array_map(function ($id) {
			return (int) $id;
		}, $roles);
	}

	/**
	 * @inheritdoc
	 */
	public function get($key) {
		return ARCOnfig::getValueByKey($key);
	}
}