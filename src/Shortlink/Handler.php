<?php

namespace srag\Plugins\Hub2\Shortlink;

use ilContext;
use ilDBInterface;
use ilHub2Plugin;
use ilInitialisation;
use ilUtil;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\ShortlinkException;

/**
 * Class Handler
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Handler
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const PLUGIN_BASE = "Customizing/global/plugins/Services/Cron/CronHook/Hub2/";
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
     * @var \ilDBInterface
     */
    private $db;
    /**
     * @var \ilCtrlInterface
     */
    private $ctrl;
    /**
     * @var \ilAuthSession
     */
    private $auth_session;
    /**
     * @var \ilObjUser
     */
    private $user;

    /**
     * Handler constructor
     */
    public function __construct(string $ext_id)
    {
        global $DIC;
        $this->db = $DIC->database();
        $this->ctrl = $DIC->ctrl();
        $this->auth_session = $DIC['ilAuthSession'];
        $this->user = $DIC->user();
        $this->init = false;
        $this->ext_id = $ext_id;
    }

    /**
     *
     */
    public function storeQuery() : void
    {
        setcookie('xhub_query', $this->ext_id, ['expires' => time() + 10]);
    }

    /**
     * @throws ShortlinkException
     */
    public function process()
    {
        if (!$this->init || !$this->db instanceof ilDBInterface) {
            throw new ShortlinkException("ILIAS not initialized, aborting...");
        }

        $object_link_factory = new ObjectLinkFactory();

        $link = $object_link_factory->findByExtId($this->ext_id);

        if (!$link->doesObjectExist()) {
            $this->sendMessage(ArConfig::getField(ArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND));
            $this->doRedirect($link->getNonExistingLink());
        }

        if (!$link->isAccessGranted()) {
            $this->sendMessage(ArConfig::getField(ArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE));
            $this->doRedirect($link->getAccessDeniedLink());
        }
        $this->sendMessage(ArConfig::getField(ArConfig::KEY_SHORTLINK_SUCCESS));
        $this->doRedirect($link->getAccessGrantedExternalLink());
    }

    protected function doRedirect(string $link)
    {
        $link = $this->sanitizeLink($link);
        $this->ctrl->redirectToURL($link);
    }

    protected function sendMessage(string $message)
    {
        if ($message !== '') {
            ilUtil::sendInfo($message, true);
        }
    }

    /**
     *
     */
    public function tryILIASInit() : void
    {
        $this->prepareILIASInit();

        require_once(__DIR__ . "/Services/Init/classes/class.ilInitialisation.php");
        ilInitialisation::initILIAS();

        $this->init = true;
    }

    /**
     *
     */
    public function tryILIASInitPublic() : void
    {
        $this->prepareILIASInit();

        require_once __DIR__ . '/Services/Context/classes/class.ilContext.php';
        ilContext::init(ilContext::CONTEXT_WAC);
        require_once __DIR__ . "/Services/Init/classes/class.ilInitialisation.php";
        ilInitialisation::initILIAS();
        $ilAuthSession = $this->auth_session;
        $ilAuthSession->init();
        $ilAuthSession->regenerateId();
        $a_id = (int) ANONYMOUS_USER_ID;
        $ilAuthSession->setUserId($a_id);
        $ilAuthSession->setAuthenticated(false, $a_id);
        $this->user->setId($a_id);

        $this->init = true;
    }

    /**
     * @return mixed|string
     */
    protected function sanitizeLink(string $link)
    {
        $link = str_replace(self::PLUGIN_BASE, "", $link);
        $link = ltrim($link, "/");

        return "/{$link}";
    }

    /**
     *
     */
    protected function prepareILIASInit()
    {
        $GLOBALS['COOKIE_PATH'] = '/';
        $_GET["client_id"] = $_COOKIE['ilClientId'];
    }
}
