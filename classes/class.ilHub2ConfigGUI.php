<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class ilHub2ConfigGUI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2ConfigGUI extends ilPluginConfigGUI
{
    use Hub2Trait;

    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var \ilCtrlInterface
     */
    private $ctrl;

    public function __construct()
    {
        global $DIC;
        $this->ctrl = $DIC->ctrl();
    }

    /**
     * @param string $cmd
     */
    public function performCommand($cmd)
    {
        switch ($this->ctrl->getNextClass()) {
            case strtolower(hub2MainGUI::class):
                $h = new hub2MainGUI();
                $this->ctrl->forwardCommand($h);
                break;

            default:
                $this->ctrl->redirectByClass([hub2MainGUI::class]);
                break;
        }
    }
}
