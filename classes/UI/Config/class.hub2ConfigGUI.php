<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

//namespace srag\Plugins\Hub2\UI\Config;
use srag\Plugins\Hub2\UI\Config\ConfigFormGUI;

/**
 * Class ConfigGUI
 *
 * @package srag\Plugins\Hub2\UI\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class hub2ConfigGUI extends hub2MainGUI
{
    public const CMD_SAVE_CONFIG = 'saveConfig';
    public const CMD_CANCEL = 'cancel';

    protected function getConfigForm(): ConfigFormGUI
    {
        return new ConfigFormGUI($this);
    }

    protected function index(): void
    {
        $this->tpl->setContent($this->getConfigForm()->getHTML());
    }

    protected function saveConfig(): void
    {
        $form = $this->getConfigForm();

        if ($form->checkInput()) {
            $form->updateConfig();
            $this->tpl->setOnScreenMessage('success', $this->plugin->txt('msg_successfully_saved'), true);
            $this->ctrl->redirect($this);
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    protected function initTabs(): void
    {
        $this->tabs->activateTab(self::TAB_PLUGIN_CONFIG);
    }
}
