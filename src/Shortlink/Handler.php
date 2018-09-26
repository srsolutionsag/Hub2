<?php

namespace SRAG\Plugins\Hub2\Shortlink;

use ilContext;
use ilDBInterface;
use ilHub2Plugin;
use ilInitialisation;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Config\ArConfig;
use SRAG\Plugins\Hub2\Exception\ShortlinkException;
use SRAG\Plugins\Hub2\Exception\ShortLinkNotFoundException;

/**
 * Class Handler
 *
 * @package SRAG\Plugins\Hub2\Handler
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Handler {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const PLUGIN_BASE = "Customizing/global/plugins/Services/Cron/CronHook/Hub2/";
	/**
	 * @var bool
	 */
	protected $init = false;
	/**
	 * @var ObjectLinkFactory
	 */
	protected $object_link_factory;
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
		$this->init = false;
		$this->ext_id = $ext_id;
	}


	public function storeQuery() {
		$return = setcookie('xhub_query', $this->ext_id, time() + 10);
	}


	/**
	 * @throws ShortlinkException
	 */
	public function process() {
		if (!$this->init || !self::dic()->database() instanceof ilDBInterface) {
			throw new ShortlinkException("ILIAS not initialized, aborting...");
		}

		$object_link_factory = new ObjectLinkFactory();

		$link = $object_link_factory->findByExtId($this->ext_id);

		if (!$link->doesObjectExist()) {
			$this->sendMessage(ArConfig::getShortLinkObjectNotFound());
			$this->doRedirect($link->getNonExistingLink());
		}

		if (!$link->isAccessGranted()) {
			$this->sendMessage(ArConfig::getShortLinkObjectNotAccessible());
			$this->doRedirect($link->getAccessDeniedLink());
		}
		$this->sendMessage(ArConfig::getShortlinkSuccess());
		$this->doRedirect($link->getAccessGrantedExternalLink());
	}


	/**
	 * @param string $link
	 */
	protected function doRedirect(string $link) {
		$link = $this->sanitizeLink($link);
		self::dic()->ctrl()->redirectToURL($link);
	}


	/**
	 * @param string $message
	 */
	protected function sendMessage(string $message) {
		if ($message !== '') {
			\ilUtil::sendInfo($message, true);
		}
	}


	public function tryILIASInit() {
		$this->prepareILIASInit();

		require_once("Services/Init/classes/class.ilInitialisation.php");
		\ilInitialisation::initILIAS();

		$this->init = true;
	}


	public function tryILIASInitPublic() {
		$this->prepareILIASInit();

		include_once './Services/Context/classes/class.ilContext.php';
		ilContext::init(ilContext::CONTEXT_WAC);
		require_once("Services/Init/classes/class.ilInitialisation.php");
		ilInitialisation::initILIAS();
		$ilAuthSession = self::dic()->authSession();
		$ilAuthSession->init();
		$ilAuthSession->regenerateId();
		$a_id = (int)ANONYMOUS_USER_ID;
		$ilAuthSession->setUserId($a_id);
		$ilAuthSession->setAuthenticated(false, $a_id);
		self::dic()->user()->setId($a_id);

		$this->init = true;
	}


	/**
	 * @param string $link
	 *
	 * @return mixed|string
	 */
	protected function sanitizeLink(string $link) {
		$link = str_replace(self::PLUGIN_BASE, "", $link);
		$link = ltrim($link, "/");
		$link = "/{$link}";

		return $link;
	}


	protected function prepareILIASInit() {
		$GLOBALS['COOKIE_PATH'] = '/';
		$_GET["client_id"] = $_COOKIE['ilClientId'];
	}
}
