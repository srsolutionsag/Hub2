<?php

namespace SRAG\Plugins\Hub2\Config;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class HubConfig
 *
 * @package     SRAG\Plugins\Hub2\Config
 * @author      Stefan Wanzenried <sw@studer-raimann.ch>
 * @author      Fabian Schmid <fs@studer-raimann.ch>
 *
 * @deprecated  TODO: Use ArConfig and merge it with HubConfig (Static methods). Use for each key seperate methods with corresponding datatype and default value
 */
class HubConfig implements IHubConfig {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritdoc
	 *
	 * @deprecated
	 */
	public function getOriginImplementationsPath() {
		$path = $this->get(IHubConfig::ORIGIN_IMPLEMENTATION_PATH);

		return ($path) ? $path : dirname(dirname(__DIR__)) . '/origins/';
	}


	/**
	 * @inheritdoc
	 *
	 * @deprecated
	 */
	public function getShortLinkObjectNotFound() {
		return $this->get(IHubConfig::SHORTLINK_OBJECT_NOT_FOUND);
	}


	/**
	 * @inheritdoc
	 *
	 * @deprecated
	 */
	public function getShortLinkObjectNotAccessible() {
		return $this->get(IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE);
	}


	/**
	 * @inheritDoc
	 *
	 * @deprecated
	 */
	public function getShortlinkSuccess() {
		return $this->get(IHubConfig::SHORTLINK_SUCCESS);
	}


	/**
	 * @inheritdoc
	 *
	 * @deprecated
	 */
	public function isOriginsConfigLocked() {
		return (bool)$this->get(IHubConfig::LOCK_ORIGINS_CONFIG);
	}


	/**
	 * @inheritdoc
	 *
	 * @deprecated
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
	 *
	 * @deprecated
	 */
	public function get(string $key): string {
		return ArConfig::getValueByKey($key);
	}
}
