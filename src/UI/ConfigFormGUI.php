<?php

namespace SRAG\Plugins\Hub2\UI;

use hub2ConfigGUI;
use ilCheckboxInputGUI;
use ilFormSectionHeaderGUI;
use ilHub2ConfigGUI;
use ilHub2Plugin;
use ilPropertyFormGUI;
use ilTextInputGUI;
use SRAG\Plugins\Hub2\Config\IHubConfig;
use SRAG\Plugins\Hub2\Helper\DIC;

/**
 * Class ConfigFOrmGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ConfigFormGUI extends ilPropertyFormGUI {

	use DIC;
	/**
	 * @var ilHub2ConfigGUI
	 */
	protected $parent_gui;
	/**
	 * @var IHubConfig
	 */
	protected $config;
	/**
	 * @var ilHub2Plugin
	 */
	protected $pl;


	/**
	 * @param hub2ConfigGUI $parent_gui
	 * @param IHubConfig    $config
	 */
	public function __construct($parent_gui, IHubConfig $config) {
		$this->parent_gui = $parent_gui;
		$this->config = $config;
		$this->pl = ilHub2Plugin::getInstance();
		$this->setFormAction($this->ctrl()->getFormAction($this->parent_gui));
		$this->initForm();
		$this->addCommandButton(hub2ConfigGUI::CMD_SAVE_CONFIG, $this->pl->txt('button_save'));
		$this->addCommandButton(hub2ConfigGUI::CMD_CANCEL, $this->pl->txt('button_cancel'));
		parent::__construct();
	}


	/**
	 *
	 */
	protected function initForm() {
		$this->setTitle($this->pl->txt('admin_form_title'));

		$item = new ilTextInputGUI($this->pl->txt('admin_origins_path'), IHubConfig::ORIGIN_IMPLEMENTATION_PATH);
		$item->setInfo($this->pl->txt('admin_origins_path_info'));
		$item->setValue($this->config->get(IHubConfig::ORIGIN_IMPLEMENTATION_PATH));
		$this->addItem($item);

		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_lock'), IHubConfig::LOCK_ORIGINS_CONFIG);
		$cb->setChecked($this->config->get(IHubConfig::LOCK_ORIGINS_CONFIG));
		$this->addItem($cb);

		$item = new ilFormSectionHeaderGUI();
		$item->setTitle($this->pl->txt('common_permissions'));
		$this->addItem($item);

		$item = new ilTextInputGUI($this->pl->txt('common_roles'), IHubConfig::ADMINISTRATE_HUB_ROLE_IDS);
		$item->setValue($this->config->get(IHubConfig::ADMINISTRATE_HUB_ROLE_IDS));
		$item->setInfo($this->pl->txt('admin_roles_info'));
		$this->addItem($item);

		$h = new ilFormSectionHeaderGUI();
		$h->setTitle($this->pl->txt('admin_shortlink'));
		$this->addItem($h);

		$item = new \ilTextAreaInputGUI($this->pl->txt('admin_msg_'
			. IHubConfig::SHORTLINK_OBJECT_NOT_FOUND), IHubConfig::SHORTLINK_OBJECT_NOT_FOUND);
		$item->setUseRte(false);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_OBJECT_NOT_FOUND));
		$item->setInfo($this->pl->txt('admin_msg_' . IHubConfig::SHORTLINK_OBJECT_NOT_FOUND . '_info'));
		$this->addItem($item);

		$item = new \ilTextAreaInputGUI($this->pl->txt('admin_msg_'
			. IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE), IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE);
		$item->setUseRte(false);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE));
		$item->setInfo($this->pl->txt('admin_msg_' . IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE . '_info'));
		$this->addItem($item);

		$item = new \ilTextAreaInputGUI($this->pl->txt('admin_msg_' . IHubConfig::SHORTLINK_SUCCESS), IHubConfig::SHORTLINK_SUCCESS);
		$item->setUseRte(false);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_SUCCESS));
		$item->setInfo($this->pl->txt('admin_msg_' . IHubConfig::SHORTLINK_SUCCESS . '_info'));
		$this->addItem($item);

		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle($this->pl->txt('admin_membership'));
		//		$this->addItem($h);
		//
		//		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_membership_activate'), hubCOnfig::F_MMAIL_ACTIVE);
		//		$cb->setInfo($this->pl->txt('admin_membership_activate_info'));
		//		$this->addItem($cb);
		//
		//		$mm = new ilTextInputGUI($this->pl->txt('admin_membership_mail_subject'), hubConfig::F_MMAIL_SUBJECT);
		//		$mm->setInfo($this->pl->txt('admin_membership_mail_subject_info'));
		//		$this->addItem($mm);
		//
		//		$mm = new ilTextAreaInputGUI($this->pl->txt('admin_membership_mail_msg'), hubConfig::F_MMAIL_MSG);
		//		$mm->setInfo($this->pl->txt('admin_membership_mail_msg_info'));
		//		$this->addItem($mm);
		//
		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle($this->pl->txt('admin_user_creation'));
		//		$this->addItem($h);
		//
		//		$ti = new ilTextInputGUI($this->pl->txt('admin_user_creation_standard_role'), hubConfig::F_STANDARD_ROLE);
		//		$this->addItem($ti);
		//
		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle($this->pl->txt('admin_header_sync'));
		//		$this->addItem($h);
		//
		//		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_use_async'), hubConfig::F_USE_ASYNC);
		//		$cb->setInfo($this->pl->txt('admin_use_async_info'));
		//
		//		$te = new ilTextInputGUI($this->pl->txt('admin_async_user'), hubConfig::F_ASYNC_USER);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI($this->pl->txt('admin_async_password'), hubConfig::F_ASYNC_PASSWORD);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI($this->pl->txt('admin_async_client'), hubConfig::F_ASYNC_CLIENT);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI($this->pl->txt('admin_async_cli_php'), hubConfig::F_ASYNC_CLI_PHP);
		//		$cb->addSubItem($te);
		//		$this->addItem($cb);

		//		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_import_export'), hubConfig::F_IMPORT_EXPORT);
		//		$this->addItem($cb);
	}
}
