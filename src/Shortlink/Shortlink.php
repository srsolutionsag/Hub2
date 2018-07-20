<?php

namespace SRAG\Plugins\Hub2\Shortlink;

use ilLink;
use SRAG\Plugins\Hub2\Config\ArConfig;
use SRAG\Plugins\Hub2\Config\HubConfig;
use SRAG\Plugins\Hub2\Exception\ILIASObjectNotFoundException;
use SRAG\Plugins\Hub2\Exception\ParseDataFailedException;
use SRAG\Plugins\Hub2\Exception\ShortLinkNotFoundException;
use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\Category\ARCategory;
use SRAG\Plugins\Hub2\Object\Course\ARCourse;
use SRAG\Plugins\Hub2\Object\Group\ARGroup;
use SRAG\Plugins\Hub2\Object\ObjectFactory;
use SRAG\Plugins\Hub2\Object\Session\ARSession;
use SRAG\Plugins\Hub2\Origin\OriginFactory;

/**
 * Class Shortlink
 *
 * @package SRAG\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Shortlink {

	use DIC;
	const PLUGIN_BASE = "Customizing/global/plugins/Services/Cron/CronHook/Hub2/";
	/**
	 * @var HubConfig
	 */
	protected $config;
	/**
	 * @var ARObject
	 */
	protected $object;
	/**
	 * @var string
	 */
	protected $ext_id = '';


	/**
	 * Shortlink constructor.
	 *
	 * @param $ext_id
	 *
	 * @throws ShortLinkNotFoundException
	 */
	public function __construct() {
		$this->initILIAS();
		$this->config = new HubConfig();
	}


	public function process($ext_id) {
		$this->ext_id = $ext_id;

		if (!is_string($ext_id)) {
			throw new ShortLinkNotFoundException($this->config->getShortLinkNoObject());
		}

		$this->determineObject();

		if ($this->object === null || !$this->object instanceof ARObject) {
			throw new ShortLinkNotFoundException($this->config->getShortLinkNoObject());
		}

		if ($this->object->getILIASId()) {
			$ilias_id = $this->object->getILIASId();
		} else {
			throw new ShortLinkNotFoundException($this->config->getShortLinkNoILIASId());
		}

		$this->doRedirect($ilias_id);
	}


	private function doRedirect($ilias_id) {
		$link = ilLink::_getLink($$ilias_id);
		$link = str_replace(self::PLUGIN_BASE, "", $link);
		echo $link;
		exit;
		$this->ctrl()->redirectToURL($link);
	}


	/**
	 * @return string
	 */
	private function getExtId(): string {
		return $this->ext_id;
	}


	/**
	 *
	 */
	private function initILIAS() {
		require_once "include/inc.header.php";
	}


	private function determineObject() {
		$of = new OriginFactory($this->db());
		$object = null;
		foreach ($of->getAllActive() as $origin) {
			$f = new ObjectFactory($origin);
			$object = $f->undefined($this->getExtId());
			switch (true) {
				case ($object instanceof ARSession):
				case ($object instanceof ARCategory):
				case ($object instanceof ARCourse):
				case ($object instanceof ARGroup):
					if ($object->getILIASId()) {
						break 2;
					} else {
						$object = null;
					}
			}
		}

		$this->object = $object;
	}
}
