<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

use srag\Plugins\Hub2\Translator;
use srag\Plugins\Hub2\Exception\HubException;

/**
 * @author            Fabian Schmid <fabian@sr.solutions>
 *
 * @ilCtrl_isCalledBy ilHub2DispatcherGUI: ilHub2ConfigGUI
 * @ilCtrl_calls      ilHub2DispatcherGUI: ilHub2OriginsGUI
 * @ilCtrl_calls      ilHub2DispatcherGUI: ilHub2ConfigGUI
 * @ilCtrl_calls      ilHub2DispatcherGUI: ilHub2SettingsGUI
 */
class ilHub2DispatcherGUI extends ilHub2DispatchableBaseGUI
{
    public const TAB_ORIGINS = 'tab_origins';
    public const TAB_PLUGIN_CONFIG = 'tab_plugin_config';

    public function index(): void
    {
        // Noting to do here
    }

    public function checkAccess(): void
    {
        return;
    }

    public function getTabs(): array
    {
        return [
            self::TAB_ORIGINS => $this->ctrl->getLinkTargetByClass(ilHub2OriginsGUI::class),
            self::TAB_PLUGIN_CONFIG => $this->ctrl->getLinkTargetByClass(ilHub2SettingsGUI::class),
        ];
    }

    public function getActiveTab(): ?string
    {
        return self::TAB_ORIGINS;
    }

    public function getDefaultClass(): ilHub2DispatchableGUI
    {
        return new ilHub2OriginsGUI();
    }

}
