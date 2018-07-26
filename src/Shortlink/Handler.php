<?php namespace SRAG\Plugins\Hub2\Shortlink;

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
 * Class Handler
 *
 * @package SRAG\Plugins\Hub2\Handler
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Handler {

	use DIC;
	const PLUGIN_BASE = "Customizing/global/plugins/Services/Cron/CronHook/Hub2/";
	/**
	 * @var ObjectLinkFactory
	 */
	protected $object_link_factory;
	/**
	 * @var HubConfig
	 */
	protected $config;
	/**
	 * @var string
	 */
	protected $ext_id = '';


	/**
	 * Handler constructor.
	 *
	 * @param string $ext_id
	 */
	public function __construct(string $ext_id) {
		$this->initILIAS();
		$this->ext_id = $ext_id;
		$this->config = new HubConfig();
		global $DIC;
		$this->object_link_factory = new ObjectLinkFactory($DIC->database());
	}


	public function process() {
		$link = $this->object_link_factory->findByExtId($this->ext_id);

		if (!$link->doesObjectExist()) {
			$this->sendMessage($this->config->getShortLinkNoObject());
			$this->doRedirect($link->getNonExistingLink());
		}

		if (!$link->isAccessGranted()) {
			$this->sendMessage($this->config->getShortLinkNotActive());
			$this->doRedirect($link->getAccessDeniedLink());
		}

		$this->doRedirect($link->getAccessGrantedExternalLink());
	}


	/**
	 * @param string $link
	 */
	private function doRedirect(string $link) {
		$link = str_replace(self::PLUGIN_BASE, "", $link);
		$link = ltrim($link, "/");
		$link = "/{$link}";
		$this->ctrl()->redirectToURL($link);
	}


	/**
	 * @param string $message
	 */
	private function sendMessage(string $message) {
		\ilUtil::sendFailure($message, true);
	}


	private function initILIAS() {
		require_once "include/inc.header.php";
	}
}
