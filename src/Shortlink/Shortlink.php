<?php

namespace SRAG\Plugins\Hub2\Shortlink;

use ilLink;
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
	 * @var string
	 */
	protected $ext_id = '';


	/**
	 * @param $ext_id
	 */
	public function __construct($ext_id) {
		$this->initILIAS();
		$this->ext_id = $ext_id;
	}


	/**
	 *
	 */
	public function doRedirect() {
		$of = new OriginFactory($this->db());
		$object = false;
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
					}
			}
		}
		if ($object instanceof ARObject && $object->getILIASId()) {
			$link = ilLink::_getLink($object->getILIASId());
			$link = str_replace(self::PLUGIN_BASE, "", $link);
			$this->ctrl()->redirectToURL($link);
		}
	}


	/**
	 * @return string
	 */
	public function getExtId(): string {
		return $this->ext_id;
	}


	/**
	 * @param string $ext_id
	 */
	public function setExtId(string $ext_id) {
		$this->ext_id = $ext_id;
	}


	/**
	 *
	 */
	private function initILIAS() {
		require_once "include/inc.header.php";
	}
}
