<?php

namespace SRAG\Plugins\Hub2\UI;

use hub2ConfigGUI;
use ilCheckboxInputGUI;
use ilFormSectionHeaderGUI;
use ilHub2ConfigGUI;
use ilHub2Plugin;
use ilPropertyFormGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Config\IHubConfig;

/**
 * Class ConfigFOrmGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ConfigFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var ilHub2ConfigGUI
	 */
	protected $parent_gui;
	/**
	 * @var IHubConfig
	 */
	protected $config;


	/**
	 * @param hub2ConfigGUI $parent_gui
	 * @param IHubConfig    $config
	 */
	public function __construct($parent_gui, IHubConfig $config) {
		$this->parent_gui = $parent_gui;
		$this->config = $config;
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent_gui));
		$this->initForm();
		$this->addCommandButton(hub2ConfigGUI::CMD_SAVE_CONFIG, self::plugin()->translate('button_save'));
		$this->addCommandButton(hub2ConfigGUI::CMD_CANCEL, self::plugin()->translate('button_cancel'));
		parent::__construct();
	}


	/**
	 *
	 */
	protected function initForm() {
		$this->setTitle(self::plugin()->translate('admin_form_title'));

		$item = new ilTextInputGUI(self::plugin()->translate('admin_origins_path'), IHubConfig::ORIGIN_IMPLEMENTATION_PATH);
		$item->setInfo(self::plugin()->translate('admin_origins_path_info'));
		$item->setValue($this->config->get(IHubConfig::ORIGIN_IMPLEMENTATION_PATH));
		$this->addItem($item);

		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_lock'), IHubConfig::LOCK_ORIGINS_CONFIG);
		$cb->setChecked($this->config->get(IHubConfig::LOCK_ORIGINS_CONFIG));
		$this->addItem($cb);

		$item = new ilFormSectionHeaderGUI();
		$item->setTitle(self::plugin()->translate('common_permissions'));
		$this->addItem($item);

		$item = new ilTextInputGUI(self::plugin()->translate('common_roles'), IHubConfig::ADMINISTRATE_HUB_ROLE_IDS);
		$item->setValue($this->config->get(IHubConfig::ADMINISTRATE_HUB_ROLE_IDS));
		$item->setInfo(self::plugin()->translate('admin_roles_info'));
		$this->addItem($item);

		$h = new ilFormSectionHeaderGUI();
		$h->setTitle(self::plugin()->translate('admin_shortlink'));
		$this->addItem($h);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('admin_msg_'
			. IHubConfig::SHORTLINK_OBJECT_NOT_FOUND), IHubConfig::SHORTLINK_OBJECT_NOT_FOUND);
		$item->setUseRte(false);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_OBJECT_NOT_FOUND));
		$item->setInfo(self::plugin()->translate('admin_msg_' . IHubConfig::SHORTLINK_OBJECT_NOT_FOUND . '_info'));
		$this->addItem($item);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('admin_msg_'
			. IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE), IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE);
		$item->setUseRte(false);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE));
		$item->setInfo(self::plugin()->translate('admin_msg_' . IHubConfig::SHORTLINK_OBJECT_NOT_ACCESSIBLE . '_info'));
		$this->addItem($item);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('admin_msg_' . IHubConfig::SHORTLINK_SUCCESS), IHubConfig::SHORTLINK_SUCCESS);
		$item->setUseRte(false);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_SUCCESS));
		$item->setInfo(self::plugin()->translate('admin_msg_' . IHubConfig::SHORTLINK_SUCCESS . '_info'));
		$this->addItem($item);

		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle(self::plugin()->translate('admin_membership'));
		//		$this->addItem($h);
		//
		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_membership_activate'), hubCOnfig::F_MMAIL_ACTIVE);
		//		$cb->setInfo(self::plugin()->translate('admin_membership_activate_info'));
		//		$this->addItem($cb);
		//
		//		$mm = new ilTextInputGUI(self::plugin()->translate('admin_membership_mail_subject'), hubConfig::F_MMAIL_SUBJECT);
		//		$mm->setInfo(self::plugin()->translate('admin_membership_mail_subject_info'));
		//		$this->addItem($mm);
		//
		//		$mm = new ilTextAreaInputGUI(self::plugin()->translate('admin_membership_mail_msg'), hubConfig::F_MMAIL_MSG);
		//		$mm->setInfo(self::plugin()->translate('admin_membership_mail_msg_info'));
		//		$this->addItem($mm);
		//
		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle(self::plugin()->translate('admin_user_creation'));
		//		$this->addItem($h);
		//
		//		$ti = new ilTextInputGUI(self::plugin()->translate('admin_user_creation_standard_role'), hubConfig::F_STANDARD_ROLE);
		//		$this->addItem($ti);
		//
		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle(self::plugin()->translate('admin_header_sync'));
		//		$this->addItem($h);
		//
		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_use_async'), hubConfig::F_USE_ASYNC);
		//		$cb->setInfo(self::plugin()->translate('admin_use_async_info'));
		//
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_user'), hubConfig::F_ASYNC_USER);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_password'), hubConfig::F_ASYNC_PASSWORD);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_client'), hubConfig::F_ASYNC_CLIENT);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_cli_php'), hubConfig::F_ASYNC_CLI_PHP);
		//		$cb->addSubItem($te);
		//		$this->addItem($cb);

		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_import_export'), hubConfig::F_IMPORT_EXPORT);
		//		$this->addItem($cb);
	}
}
