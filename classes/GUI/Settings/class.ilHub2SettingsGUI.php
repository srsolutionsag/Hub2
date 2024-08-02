<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

use srag\Plugins\Hub2\UI\Config\ConfigFormGUI;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class ilHub2SettingsGUI extends ilHub2DispatchableBaseGUI
{
    public const CMD_SAVE_CONFIG = 'saveConfig';
    public const CMD_CANCEL = 'cancel';
    private ConfigFormGUI $form;

    public function __construct()
    {
        parent::__construct();
        $this->form = new ConfigFormGUI($this);
    }


    public function index(): void
    {
        $this->main_tpl->setContent($this->form->getHTML());
    }

    protected function saveConfig(): void
    {
        $form = $this->form;

        if ($form->checkInput()) {
            $form->updateConfig();
            $this->main_tpl->setOnScreenMessage('success', $this->plugin->txt('msg_successfully_saved'), true);
            $this->ctrl->redirect($this);
        }
        $form->setValuesByPost();
        $this->main_tpl->setContent($form->getHTML());
    }

    public function getDefaultClass(): ilHub2DispatchableGUI
    {
        return $this;
    }

    public function checkAccess(): void
    {
        // TODO: Implement checkAccess() method.
    }

    public function getActiveTab(): ?string
    {
        return ilHub2DispatcherGUI::TAB_PLUGIN_CONFIG;
    }

}
