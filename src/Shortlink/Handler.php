<?php namespace SRAG\Plugins\Hub2\Shortlink;

use ilAuthSession;
use ilContext;
use RESTController\libs\ilInitialisation;
use SRAG\Plugins\Hub2\Config\HubConfig;
use SRAG\Plugins\Hub2\Exception\ShortlinkException;
use SRAG\Plugins\Hub2\Exception\ShortLinkNotFoundException;
use SRAG\Plugins\Hub2\Helper\DIC;

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
	 * @var bool
	 */
	protected $init = false;
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
		$this->init = false;
		$this->ext_id = $ext_id;
		$this->config = new HubConfig();
	}


	public function storeQuery() {
		$return = setcookie('xhub_query', $this->ext_id, time() + 10);
	}


	/**
	 * @throws ShortlinkException
	 */
	public function process() {
		global $DIC;
		if (!$this->init || !$DIC->database() instanceof \ilDBInterface) {
			throw new ShortlinkException("ILIAS not initialized, aborting...");
		}

		$object_link_factory = new ObjectLinkFactory($DIC->database());

		$link = $object_link_factory->findByExtId($this->ext_id);

		if (!$link->doesObjectExist()) {
			$this->sendMessage((string)$this->config->getShortLinkObjectNotFound());
			$this->doRedirect($link->getNonExistingLink());
		}

		if (!$link->isAccessGranted()) {
			$this->sendMessage((string)$this->config->getShortLinkObjectNotAccessible());
			$this->doRedirect($link->getAccessDeniedLink());
		}
		$this->sendMessage((string)$this->config->getShortlinkSuccess());
		$this->doRedirect($link->getAccessGrantedExternalLink());
	}


	/**
	 * @param string $link
	 */
	protected function doRedirect(string $link) {
		$link = $this->sanitizeLink($link);
		$this->ctrl()->redirectToURL($link);
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

		global $DIC;
		include_once './Services/Context/classes/class.ilContext.php';
		ilContext::init(ilContext::CONTEXT_WAC);
		require_once("Services/Init/classes/class.ilInitialisation.php");
		ilInitialisation::initILIAS();
		/**
		 * @var ilAuthSession $ilAuthSession
		 */
		$ilAuthSession = $DIC['ilAuthSession'];
		$ilAuthSession->init();
		$ilAuthSession->regenerateId();
		$a_id = (int)ANONYMOUS_USER_ID;
		$ilAuthSession->setUserId($a_id);
		$ilAuthSession->setAuthenticated(false, $a_id);
		$DIC->user()->setId($a_id);

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
