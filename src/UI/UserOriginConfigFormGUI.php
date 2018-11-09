<?php

namespace srag\Plugins\Hub2\UI;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilTextareaInputGUI;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\IUserOriginConfig;
use srag\Plugins\Hub2\Origin\Config\UserOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\UserOriginProperties;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;

/**
 * Class UserOriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARUserOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		parent::addSyncConfig();
		$syncfield = new ilSelectInputGUI(self::plugin()->translate('usr_config_login_field'), $this->conf(IUserOriginConfig::LOGIN_FIELD));
		$options = [];
		foreach (UserOriginConfig::getAvailableLoginFields() as $id) {
			$options[$id] = self::plugin()->translate('usr_config_login_field_' . $id);
		}
		$syncfield->setOptions($options);
		$syncfield->setInfo(self::plugin()->translate('usr_config_login_field_info'));
		$syncfield->setRequired(true);
		$syncfield->setValue($this->origin->config()->getILIASLoginField());
		$this->addItem($syncfield);
	}


	protected function addPropertiesNew() {
		$activate = new ilCheckboxInputGUI(self::plugin()
			->translate('usr_prop_activate_account'), $this->prop(UserOriginProperties::ACTIVATE_ACCOUNT));
		$activate->setChecked($this->origin->properties()->get(UserOriginProperties::ACTIVATE_ACCOUNT));
		$this->addItem($activate);
		//
		$cb = new ilCheckboxInputGUI(self::plugin()->translate('usr_prop_create_password'), $this->prop(UserOriginProperties::CREATE_PASSWORD));
		$cb->setChecked($this->origin->properties()->get(UserOriginProperties::CREATE_PASSWORD));
		$this->addItem($cb);
		$send_password = new ilCheckboxInputGUI(self::plugin()
			->translate('usr_prop_send_password'), $this->prop(UserOriginProperties::SEND_PASSWORD));
		$send_password->setChecked($this->origin->properties()->get(UserOriginProperties::SEND_PASSWORD));
		//		$syncfield = new ilSelectInputGUI(self::plugin()->translate('usr_prop_send_password_field'), $this->prop(UserOriginProperties::SEND_PASSWORD_FIELD));
		//		$opt = array('email'            => 'email',
		//		             'external_account' => 'external_account',
		//		             'email_password'   => 'email_password',);
		//		$syncfield->setOptions($opt);
		//		$syncfield->setValue(
		//			$this->origin->properties()
		//				->get(UserOriginProperties::SEND_PASSWORD_FIELD)
		//		);
		//		$activate->addSubItem($syncfield);

		$subject = new ilTextInputGUI(self::plugin()
			->translate('usr_prop_password_mail_subject'), $this->prop(UserOriginProperties::PASSWORD_MAIL_SUBJECT));
		$subject->setValue($this->origin->properties()->get(UserOriginProperties::PASSWORD_MAIL_SUBJECT));
		$send_password->addSubItem($subject);
		$mail_body = new ilTextareaInputGUI(self::plugin()
			->translate('usr_prop_password_mail_body'), $this->prop(UserOriginProperties::PASSWORD_MAIL_BODY));
		$mail_body->setInfo(self::plugin()->translate('usr_prop_password_mail_placeholders') . ': [LOGIN], [PASSWORD]');
		$mail_body->setCols(80);
		$mail_body->setRows(15);
		$mail_body->setValue($this->origin->properties()->get(UserOriginProperties::PASSWORD_MAIL_BODY));
		$send_password->addSubItem($mail_body);
		$mail_date_format = new ilTextInputGUI(self::plugin()
			->translate('usr_prop_password_mail_date_format'), $this->prop(UserOriginProperties::PASSWORD_MAIL_DATE_FORMAT));
		$mail_date_format->setInfo('<a target=\'_blank\' href=\'http://php.net/manual/de/function.date.php\'>' . htmlspecialchars(self::plugin()
				->translate('usr_prop_password_mail_date_format_info')) . '</a>');
		$mail_date_format->setValue($this->origin->properties()->get(UserOriginProperties::PASSWORD_MAIL_DATE_FORMAT));
		$send_password->addSubItem($mail_date_format);
		$this->addItem($send_password);

		parent::addPropertiesNew();
	}


	protected function addPropertiesUpdate() {
		$activate = new ilCheckboxInputGUI(self::plugin()
			->translate('usr_prop_reactivate_account'), $this->prop(UserOriginProperties::REACTIVATE_ACCOUNT));
		$activate->setInfo(self::plugin()->translate('usr_prop_reactivate_account_info'));
		$activate->setChecked($this->origin->properties()->get(UserOriginProperties::REACTIVATE_ACCOUNT));
		$this->addItem($activate);

		$activate = new ilCheckboxInputGUI(self::plugin()
			->translate('usr_prop_resend_password'), $this->prop(UserOriginProperties::RE_SEND_PASSWORD));
		$activate->setInfo(self::plugin()->translate('usr_prop_resend_password_info'));
		$activate->setChecked($this->origin->properties()->get(UserOriginProperties::RE_SEND_PASSWORD));
		$this->addItem($activate);

		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new ilRadioGroupInputGUI(self::plugin()->translate('usr_prop_delete_mode'), $this->prop(UserOriginProperties::DELETE));
		$opt = new ilRadioOption(self::plugin()->translate('usr_prop_delete_mode_none'), UserOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);
		$opt = new ilRadioOption(self::plugin()->translate('usr_prop_delete_mode_inactive'), UserOriginProperties::DELETE_MODE_INACTIVE);
		$delete->addOption($opt);
		$opt = new ilRadioOption(self::plugin()->translate('usr_prop_delete_mode_delete'), UserOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);
		$delete->setValue($this->origin->properties()->get(UserOriginProperties::DELETE));
		$this->addItem($delete);

		parent::addPropertiesDelete();
	}
}
