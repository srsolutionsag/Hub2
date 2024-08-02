<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

use ILIAS\DI\UIServices;
use ILIAS\HTTP\Services;
use srag\Plugins\Hub2\Translator;
use srag\Plugins\Hub2\Exception\HubException;

abstract class ilHub2DispatchableBaseGUI implements ilHub2DispatchableGUI
{
    protected ilHub2Plugin $plugin;
    protected ilRbacReview $rbac_review;
    protected ilToolbarGUI $toolbar;
    protected UIServices $ui;
    protected ilObjUser $user;
    protected Services $http;
    protected \ilCtrlInterface $ctrl;
    protected ilTabsGUI $tabs;
    protected Translator $translator;
    protected \ilGlobalTemplateInterface $main_tpl;

    public function __construct()
    {
        global $DIC;
        $this->ctrl = $DIC->ctrl();
        $this->tabs = $DIC->tabs();
        $this->main_tpl = $DIC->ui()->mainTemplate();
        $this->http = $DIC->http();
        $this->plugin = ilHub2Plugin::getInstance();
        $this->translator = $this->plugin; // TODO move to translator
        $this->user = $DIC->user();
        $this->ui = $DIC->ui();
        $this->toolbar = $DIC->toolbar();
        $this->rbac_review = $DIC->rbac()->review();
    }

    /**
     * This is the Main-entryponit for the GUI in HUB2
     * It forwards to the correct GUI-Class and takes care of the structure of tabs
     * - Origins
     * -- Origin-Settings
     * -- Status
     * -- Logs
     * - Settings
     */
    public function executeCommand(): void
    {
        if (!$this->ctrl) {
            $this->checkAccess();
        }
        $this->initTabs();
        $next_class = $this->ctrl->getNextClass();

        if ($next_class === null || $next_class === '' || $next_class === '0' || $next_class === strtolower(get_class($this))) {
            $default = $this->getDefaultClass();
            if (get_class($this) === get_class($default)) {
                $command = $this->ctrl->getCmd(ilHub2DispatchableGUI::CMD_INDEX);
                $this->{$command}();
                return;
            }
            $this->ctrl->forwardCommand($default);

            return;
        }
        $classes = $this->ctrl->getCurrentClassPath();
        // get the last class in the path
        $class_name = array_pop($classes);
        $instance = $next_class === strtolower($class_name) ? new $class_name() : $this->getDefaultClass();
        $this->ctrl->forwardCommand($instance);
    }

    /**
     * @throws HubException
     */
    abstract public function checkAccess(): void;

    protected function initTabs(): void
    {
        if (!$this instanceof ilHub2DispatchableGUI) {
            return;
        }

        foreach ($this->getTabs() as $tab => $target) {
            $this->tabs->addTab(
                $tab,
                $this->translator->txt($tab),
                $target
            );
        }

        foreach ($this->getSubtabs() as $subtab => $target) {
            $this->tabs->addSubTab(
                $subtab,
                $this->translator->txt($subtab),
                $target
            );
        }

        $active_tab = $this->getActiveTab();
        if ($active_tab) {
            $this->tabs->activateTab($active_tab);
        }
        $active_subtab = $this->getActiveSubTab();
        if ($active_subtab) {
            $this->tabs->activateSubTab($active_subtab);
        }
    }

    public function getActiveTab(): ?string
    {
        return null;
    }

    public function getDefaultClass(): ilHub2DispatchableGUI
    {
        return $this;
    }

    public function getSubtabs(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [];
    }

    public function getActiveSubTab(): ?string
    {
        return null;
    }
}
