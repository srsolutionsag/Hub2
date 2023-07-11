<?php

//namespace srag\Plugins\Hub2\UI;
require_once __DIR__ . '/../../vendor/autoload.php';

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class MainGUI
 * @package           srag\Plugins\Hub2\UI
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @ilCtrl_IsCalledBy hub2MainGUI: ilHub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigOriginsGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2CustomViewGUI
 * @ilCtrl_Calls      hub2MainGUI: ilPropertyFormGUI
 */
class hub2MainGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const TAB_PLUGIN_CONFIG = 'tab_plugin_config';
    public const TAB_ORIGINS = 'tab_origins';
    public const TAB_CUSTOM_VIEWS = 'admin_tab_custom_views';
    public const CMD_INDEX = 'index';
    /**
     * @var \ILIAS\DI\UIServices
     */
    protected $ui;
    /**
     * @var \ILIAS\DI\HTTPServices
     */
    protected $http;
    /**
     * @var ilRbacReview
     */
    protected $rbac_review;
    /**
     * @var ilGlobalTemplateInterface
     */
    protected $tpl;
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilTabsGUI
     */
    protected $tabs;
    /**
     * @var ilHub2Plugin
     */
    protected $plugin;

    /**
     * MainGUI constructor
     */
    public function __construct()
    {
        global $DIC;
        $this->tpl = $DIC['tpl'];
        $this->ctrl = $DIC['ilCtrl'];
        $this->tabs = $DIC['ilTabs'];
        $this->plugin = ilHub2Plugin::getInstance();
        $this->rbac_review = $DIC->rbac()->review();
        $this->http = $DIC->http();
        $this->ui = $DIC->ui();
    }

    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->initTabs();
        $nextClass = $this->ctrl->getNextClass();
        $this->tpl->setTitleIcon('./Customizing/global/plugins/Services/Cron/CronHook/Hub2/templates/hub2_icon.svg');
        switch ($nextClass) {
            case strtolower(hub2ConfigGUI::class):
                $this->ctrl->forwardCommand(new hub2ConfigGUI());
                break;
            case strtolower(hub2ConfigOriginsGUI::class):
                $this->ctrl->forwardCommand(new hub2ConfigOriginsGUI());
                break;
            case strtolower(hub2CustomViewGUI::class):
                $this->tabs->activateTab(self::TAB_CUSTOM_VIEWS);
                $this->ctrl->forwardCommand(new hub2CustomViewGUI());
                break;
            case strtolower(hub2DataGUI::class):
            case strtolower(hub2LogsGUI::class):
                break;
            default:
                $cmd = $this->ctrl->getCmd(self::CMD_INDEX);
                $this->{$cmd}();
        }
    }

    /**
     *
     */
    protected function index()/*: void*/
    {
        $this->ctrl->redirectByClass(hub2ConfigOriginsGUI::class);
    }

    /**
     *
     */
    protected function initTabs()/*: void*/
    {
        $this->tabs->addTab(
            self::TAB_ORIGINS,
            $this->plugin->txt(self::TAB_ORIGINS),
            $this->ctrl
                ->getLinkTargetByClass(hub2ConfigOriginsGUI::class)
        );

        $this->tabs->addTab(
            self::TAB_PLUGIN_CONFIG,
            $this->plugin->txt(self::TAB_PLUGIN_CONFIG),
            $this->ctrl
                ->getLinkTargetByClass(hub2ConfigGUI::class)
        );

        if (ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_ACTIVE)) {
            $this->tabs->addTab(
                self::TAB_CUSTOM_VIEWS,
                $this->plugin->txt(self::TAB_CUSTOM_VIEWS),
                $this->ctrl
                    ->getLinkTargetByClass(hub2CustomViewGUI::class)
            );
        }
    }

    /**
     *
     */
    protected function cancel()/*: void*/
    {
        $this->index();
    }

    /**
     *
     */
    protected function handleExplorerCommand()/*: void*/
    {
        (new OriginConfigFormGUI(
            new hub2ConfigOriginsGUI(),
            new OriginRepository(),
            (new OriginFactory())->getById((int) filter_input(INPUT_GET, hub2ConfigOriginsGUI::ORIGIN_ID))
        ))->getILIASFileRepositorySelector()
          ->handleExplorerCommand();
    }
}
