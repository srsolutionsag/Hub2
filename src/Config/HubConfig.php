<?php

namespace SRAG\Plugins\Hub2\Config;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class HubConfig
 *
 * @package SRAG\Plugins\Hub2\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class HubConfig implements IHubConfig {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritdoc
	 */
	public function getOriginImplementationsPath() {
		$path = $this->get(IHubConfig::ORIGIN_IMPLEMENTATION_PATH);

		return ($path) ? $path : dirname(dirname(__DIR__)) . '/origins/';
	}


	/**
	 * @inheritdoc
	 */
	public function getShortLinkObjectNotFound() {
		return $this->get(IHubConfig::SHORTLINK_OBJECT_NOT_FOUND);
	}


	/**
	 * @inheritdoc
	 */
	public function getShortLinkObjectNotAccessible() {
		return $this->get(IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE);
	}


	/**
	 * @inheritDoc
	 */
	public function getShortlinkSuccess() {
		return $this->get(IHubConfig::SHORTLINK_SUCCESS);
	}


	/**
	 * @inheritdoc
	 */
	public function isOriginsConfigLocked() {
		return (bool)$this->get(IHubConfig::LOCK_ORIGINS_CONFIG);
	}


	/**
	 * @inheritdoc
	 */
	public function getAdministrationRoleIds() {
		$roles = $this->get(IHubConfig::ADMINISTRATE_HUB_ROLE_IDS);
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
