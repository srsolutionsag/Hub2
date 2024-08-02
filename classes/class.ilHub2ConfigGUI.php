<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Class ilHub2ConfigGUI
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy ilHub2ConfigGUI: ilObjComponentSettingsGUI, ilUIPluginRouterGUI
 */
class ilHub2ConfigGUI extends ilPluginConfigGUI
{
    private \ilCtrlInterface $ctrl;

    public function __construct()
    {
        global $DIC;
        $this->ctrl = $DIC->ctrl();
    }

    public function performCommand(string $cmd): void
    {
        switch ($this->ctrl->getNextClass()) {
            case strtolower(ilHub2DispatcherGUI::class):
                $h = new ilHub2DispatcherGUI();
                $this->ctrl->forwardCommand($h);
                break;

            default:
                $this->ctrl->redirectByClass([ilHub2DispatcherGUI::class]);
                break;
        }
    }
}
