<?php

//namespace srag\Plugins\Hub2\UI\Config;

use srag\Plugins\Hub2\UI\Config\ConfigFormGUI;

/**
 * Class ConfigGUI
 * @package srag\Plugins\Hub2\UI\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class hub2ConfigGUI extends hub2MainGUI
{
    public const CMD_SAVE_CONFIG = 'saveConfig';
    public const CMD_CANCEL = 'cancel';

    /**
     * @return ConfigFormGUI
     */
    protected function getConfigForm(): ConfigFormGUI
    {
        $form = new ConfigFormGUI($this);

        return $form;
    }

    /**
     *
     */
    protected function index()/*: void*/
    {
        $form = $this->getConfigForm();
        $this->tpl->setContent($form->getHTML());
    }

    /**
     *
     */
    protected function saveConfig()/*: void*/
    {
        $form = $this->getConfigForm();

        if ($form->checkInput()) {
            $form->updateConfig();
            ilUtil::sendSuccess($this->plugin->txt('msg_successfully_saved'), true);
            $this->ctrl->redirect($this);
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    /**
     *
     */
    protected function initTabs()/*: void*/
    {
        $this->tabs->activateTab(self::TAB_PLUGIN_CONFIG);
    }
}
