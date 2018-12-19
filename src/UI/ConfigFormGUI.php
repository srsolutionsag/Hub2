<?php

namespace srag\Plugins\Hub2\UI;

use hub2ConfigGUI;
use ilCheckboxInputGUI;
use ilFormPropertyGUI;
use ilFormSectionHeaderGUI;
use ilHub2ConfigGUI;
use ilHub2Plugin;
use ilPropertyFormGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Config\IArConfig;

/**
 * Class ConfigFOrmGUI
 *
 * @package srag\Plugins\Hub2\UI
 *
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
	 * @param hub2ConfigGUI $parent_gui
	 */
	public function __construct(hub2ConfigGUI $parent_gui) {
		parent::__construct();

		$this->parent_gui = $parent_gui;

		$this->initForm();
	}


	/**
	 *
	 */
	protected function initForm()/*: void*/ {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent_gui));

		$this->addCommandButton(hub2ConfigGUI::CMD_SAVE_CONFIG, self::plugin()->translate('button_save'));
		$this->addCommandButton(hub2ConfigGUI::CMD_CANCEL, self::plugin()->translate('button_cancel'));

		$this->setTitle(self::plugin()->translate('admin_form_title'));

		$item = new ilTextInputGUI(self::plugin()->translate('admin_origins_path'), IArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH);
		$item->setInfo(self::plugin()->translate('admin_origins_path_info'));
		$item->setValue(ArConfig::getOriginImplementationsPath());
		$this->addItem($item);

		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_lock'), IArConfig::KEY_LOCK_ORIGINS_CONFIG);
		$cb->setChecked(ArConfig::isOriginsConfigLocked());
		$this->addItem($cb);

		$item = new ilFormSectionHeaderGUI();
		$item->setTitle(self::plugin()->translate('common_permissions'));
		$this->addItem($item);

		$item = new ilTextInputGUI(self::plugin()->translate('common_roles'), IArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS);
		$item->setValue(implode(', ', ArConfig::getAdministrationRoleIds())); // TODO: Use better config gui for getAdministrationRoleIds
		$item->setInfo(self::plugin()->translate('admin_roles_info'));
		$this->addItem($item);

		$h = new ilFormSectionHeaderGUI();
		$h->setTitle(self::plugin()->translate('admin_shortlink'));
		$this->addItem($h);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('admin_msg_'
			. IArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND), IArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND);
		$item->setUseRte(false);
		$item->setValue(ArConfig::getShortLinkObjectNotFound());
		$item->setInfo(self::plugin()->translate('admin_msg_' . IArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND . '_info'));
		$this->addItem($item);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('admin_msg_'
			. IArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE), IArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE);
		$item->setUseRte(false);
		$item->setValue(ArConfig::getShortLinkObjectNotAccessible());
		$item->setInfo(self::plugin()->translate('admin_msg_' . IArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE . '_info'));
		$this->addItem($item);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('admin_msg_' . IArConfig::KEY_SHORTLINK_SUCCESS), IArConfig::KEY_SHORTLINK_SUCCESS);
		$item->setUseRte(false);
		$item->setValue(ArConfig::getShortlinkSuccess());
		$item->setInfo(self::plugin()->translate('admin_msg_' . IArConfig::KEY_SHORTLINK_SUCCESS . '_info'));
		$this->addItem($item);

		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle(self::plugin()->translate('admin_membership'));
		//		$this->addItem($h);
		//
		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_membership_activate'), IArConfig::KEY_MMAIL_ACTIVE);
		//		$cb->setInfo(self::plugin()->translate('admin_membership_activate_info'));
		//		$this->addItem($cb);
		//
		//		$mm = new ilTextInputGUI(self::plugin()->translate('admin_membership_mail_subject'), IArConfig::KEY_MMAIL_SUBJECT);
		//		$mm->setInfo(self::plugin()->translate('admin_membership_mail_subject_info'));
		//		$this->addItem($mm);
		//
		//		$mm = new ilTextAreaInputGUI(self::plugin()->translate('admin_membership_mail_msg'), IArConfig::KEY_MMAIL_MSG);
		//		$mm->setInfo(self::plugin()->translate('admin_membership_mail_msg_info'));
		//		$this->addItem($mm);
		//
		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle(self::plugin()->translate('admin_user_creation'));
		//		$this->addItem($h);
		//
		//		$ti = new ilTextInputGUI(self::plugin()->translate('admin_user_creation_standard_role'), IArConfig::KEY_STANDARD_ROLE);
		//		$this->addItem($ti);
		//
		//		$h = new ilFormSectionHeaderGUI();
		//		$h->setTitle(self::plugin()->translate('admin_header_sync'));
		//		$this->addItem($h);
		//
		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_use_async'), IArConfig::KEY_USE_ASYNC);
		//		$cb->setInfo(self::plugin()->translate('admin_use_async_info'));
		//
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_user'), IArConfig::KEY_ASYNC_USER);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_password'), IArConfig::KEY_ASYNC_PASSWORD);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_client'), IArConfig::KEY_ASYNC_CLIENT);
		//		$cb->addSubItem($te);
		//		$te = new ilTextInputGUI(self::plugin()->translate('admin_async_cli_php'), IArConfig::KEY_ASYNC_CLI_PHP);
		//		$cb->addSubItem($te);
		//		$this->addItem($cb);

		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('admin_import_export'), IArConfig::KEY_IMPORT_EXPORT);
		//		$this->addItem($cb);
	}


	/**
	 *
	 */
	public function updateConfig()/*: void*/ {
		foreach ($this->getInputItemsRecursive() as $item) {
			/** @var ilFormPropertyGUI $item */
			switch ($item->getPostVar()) {
				case IArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH:
					ArConfig::setOriginImplementationsPath($this->getInput($item->getPostVar()));
					break;
				case IArConfig::KEY_LOCK_ORIGINS_CONFIG:
					ArConfig::setOriginsConfigLocked($this->getInput($item->getPostVar()));
					break;
				case IArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS:
					$administration_role_ids = $this->getInput($item->getPostVar());
					$administration_role_ids = preg_split('/, */', $administration_role_ids);
					$administration_role_ids = array_map(function (string $id): int {
						return intval($id);
					}, $administration_role_ids);
					ArConfig::setAdministrationRoleIds($administration_role_ids); // TODO: Use better config gui for getAdministrationRoleIds
					break;
				case IArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND:
					ArConfig::setShortLinkObjectNotFound($this->getInput($item->getPostVar()));
					break;
				case IArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE:
					ArConfig::setShortLinkObjectNotAccessible($this->getInput($item->getPostVar()));
					break;
				case IArConfig::KEY_SHORTLINK_SUCCESS:
					ArConfig::setShortlinkSuccess($this->getInput($item->getPostVar()));
					break;
				default:
					break;
			}
		}
	}
}
