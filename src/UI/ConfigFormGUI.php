<?php namespace SRAG\Hub2\UI;

use SRAG\Hub2\Config\IHubConfig;

/**
 * Class ConfigFOrmGUI
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\UI
 */
class ConfigFormGUI extends \ilPropertyFormGUI {

	/**
	 * @var \ilHubConfigGUI
	 */
	protected $parent_gui;
	/**
	 * @var  \ilCtrl
	 */
	protected $ctrl;

	/**
	 * @var IHubConfig
	 */
	protected $config;

	/**
	 * @var \ilHub2Plugin
	 */
	protected $pl;
	/**
	 * @var \ilLanguage
	 */
	protected $lng;


	/**
	 * @param $parent_gui
	 * @param IHubConfig $config
	 */
	public function __construct($parent_gui, IHubConfig $config) {
		global $DIC;
		$this->parent_gui = $parent_gui;
		$this->config = $config;
		$this->pl = \ilHub2Plugin::getInstance();
		$this->lng = $DIC->language();
		$this->setFormAction($DIC->ctrl()->getFormAction($this->parent_gui));
		$this->initForm();
		$this->addCommandButton('saveConfig', $this->lng->txt('save'));
		$this->addCommandButton('cancel', $this->lng->txt('cancel'));
	}

	protected function initForm() {
		$this->setTitle($this->lng->txt('configuration'));

		$item = new \ilTextInputGUI($this->pl->txt('admin_origins_path'), IHubConfig::ORIGIN_IMPLEMENTATION_PATH);
		$item->setInfo($this->pl->txt('admin_origins_path_info'));
		$item->setValue($this->config->get(IHubConfig::ORIGIN_IMPLEMENTATION_PATH));
		$this->addItem($item);

//		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_lock'), hubConfig::F_LOCK);
//		$this->addItem($cb);

		$item = new \ilFormSectionHeaderGUI();
		$item->setTitle($this->lng->txt('permissions'));
		$this->addItem($item);

		$item = new \ilTextInputGUI($this->lng->txt('roles'), IHubConfig::ADMINISTRATE_HUB_ROLE_IDS);
		$item->setValue($this->config->get(IHubConfig::ADMINISTRATE_HUB_ROLE_IDS));
		$item->setInfo($this->pl->txt('admin_roles_info'));
		$this->addItem($item);

		$h = new \ilFormSectionHeaderGUI();
		$h->setTitle($this->pl->txt('admin_shortlink'));
		$this->addItem($h);

		$item = new \ilTextInputGUI($this->pl->txt('admin_msg_shortlink_not_found'), IHubConfig::SHORTLINK_NOT_FOUND);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_NOT_FOUND));
		$item->setInfo($this->pl->txt('admin_msg_shortlink_not_found_info'));
		$this->addItem($item);

		$item = new \ilTextInputGUI($this->pl->txt('admin_msg_shortlink_no_ilias_id'), IHubConfig::SHORTLINK_NO_ILIAS_ID);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_NO_ILIAS_ID));
		$item->setInfo($this->pl->txt('admin_msg_shortlink_no_ilias_id_info'));
		$this->addItem($item);

		$item = new \ilTextInputGUI($this->pl->txt('admin_msg_shortlink_not_active'), IHubConfig::SHORTLINK_NOT_ACTIVE);
		$item->setValue($this->config->get(IHubConfig::SHORTLINK_NOT_ACTIVE));
		$item->setInfo($this->pl->txt('admin_msg_shortlink_not_active'));
		$this->addItem($item);


//		$h = new \ilFormSectionHeaderGUI();
//		$h->setTitle($this->pl->txt('admin_membership'));
//		$this->addItem($h);
//
//		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_membership_activate'), hubCOnfig::F_MMAIL_ACTIVE);
//		$cb->setInfo($this->pl->txt('admin_membership_activate_info'));
//		$this->addItem($cb);
//
//		$mm = new \ilTextInputGUI($this->pl->txt('admin_membership_mail_subject'), hubConfig::F_MMAIL_SUBJECT);
//		$mm->setInfo($this->pl->txt('admin_membership_mail_subject_info'));
//		$this->addItem($mm);
//
//		$mm = new ilTextAreaInputGUI($this->pl->txt('admin_membership_mail_msg'), hubConfig::F_MMAIL_MSG);
//		$mm->setInfo($this->pl->txt('admin_membership_mail_msg_info'));
//		$this->addItem($mm);
//
//		$h = new \ilFormSectionHeaderGUI();
//		$h->setTitle($this->pl->txt('admin_user_creation'));
//		$this->addItem($h);
//
//		$ti = new \ilTextInputGUI($this->pl->txt('admin_user_creation_standard_role'), hubConfig::F_STANDARD_ROLE);
//		$this->addItem($ti);
//
//		$h = new \ilFormSectionHeaderGUI();
//		$h->setTitle($this->pl->txt('admin_header_sync'));
//		$this->addItem($h);
//
//		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_use_async'), hubConfig::F_USE_ASYNC);
//		$cb->setInfo($this->pl->txt('admin_use_async_info'));
//
//		$te = new \ilTextInputGUI($this->pl->txt('admin_async_user'), hubConfig::F_ASYNC_USER);
//		$cb->addSubItem($te);
//		$te = new \ilTextInputGUI($this->pl->txt('admin_async_password'), hubConfig::F_ASYNC_PASSWORD);
//		$cb->addSubItem($te);
//		$te = new \ilTextInputGUI($this->pl->txt('admin_async_client'), hubConfig::F_ASYNC_CLIENT);
//		$cb->addSubItem($te);
//		$te = new \ilTextInputGUI($this->pl->txt('admin_async_cli_php'), hubConfig::F_ASYNC_CLI_PHP);
//		$cb->addSubItem($te);
//		$this->addItem($cb);

//		$cb = new ilCheckboxInputGUI($this->pl->txt('admin_import_export'), hubConfig::F_IMPORT_EXPORT);
//		$this->addItem($cb);
	}
}