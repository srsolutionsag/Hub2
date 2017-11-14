<?php namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Origin\ARUserOrigin;
use SRAG\Plugins\Hub2\Origin\Config\IUserOriginConfig;
use SRAG\Plugins\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\UserOriginProperties;

/**
 * Class UserOriginConfigFormGUI
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\UI
 */
class UserOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARUserOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		parent::addSyncConfig();
		$syncfield = new \ilSelectInputGUI($this->pl->txt('usr_config_login_field'), $this->conf(IUserOriginConfig::LOGIN_FIELD));
		$options = [];
		foreach (UserOriginConfig::getAvailableLoginFields() as $id) {
			$options[$id] = $this->pl->txt('usr_config_login_field_' . $id);
		}
		$syncfield->setOptions($options);
		$syncfield->setInfo($this->pl->txt('usr_config_login_field_info'));
		$syncfield->setRequired(true);
		$syncfield->setValue($this->origin->config()->getILIASLoginField());
		$this->addItem($syncfield);
	}


	protected function addPropertiesNew() {
		$activate = new \ilCheckboxInputGUI($this->pl->txt('usr_prop_activate_account'), $this->prop(UserOriginProperties::ACTIVATE_ACCOUNT));
		$activate->setChecked($this->origin->properties()->get(UserOriginProperties::ACTIVATE_ACCOUNT));
		$this->addItem($activate);
		//
		$cb = new \ilCheckboxInputGUI($this->pl->txt('usr_prop_create_password'), $this->prop(UserOriginProperties::CREATE_PASSWORD));
		$cb->setChecked($this->origin->properties()->get(UserOriginProperties::CREATE_PASSWORD));
		$this->addItem($cb);
		$send_password = new \ilCheckboxInputGUI($this->pl->txt('usr_prop_send_password'), $this->prop(UserOriginProperties::SEND_PASSWORD));
		$send_password->setChecked($this->origin->properties()->get(UserOriginProperties::SEND_PASSWORD));
		$syncfield = new \ilSelectInputGUI($this->pl->txt('usr_prop_send_password_field'), $this->prop(UserOriginProperties::SEND_PASSWORD_FIELD));
		$opt = array(
			'email'            => 'email',
			'external_account' => 'external_account',
			'email_password'   => 'email_password',
		);
		$syncfield->setOptions($opt);
		$syncfield->setValue($this->origin->properties()->get(UserOriginProperties::SEND_PASSWORD_FIELD));
		$activate->addSubItem($syncfield);

		$subject = new \ilTextInputGUI($this->pl->txt('usr_prop_password_mail_subject'), $this->prop(UserOriginProperties::PASSWORD_MAIL_SUBJECT));
		$subject->setValue($this->origin->properties()->get(UserOriginProperties::PASSWORD_MAIL_SUBJECT));
		$send_password->addSubItem($subject);
		$mail_body = new \ilTextareaInputGUI($this->pl->txt('usr_prop_password_mail_body'), $this->prop(UserOriginProperties::PASSWORD_MAIL_BODY));
		$mail_body->setInfo($this->pl->txt('usr_prop_password_mail_placeholders')
		                    . ': [LOGIN], [PASSWORD], [VALID_UNTIL], [COURSE_LINK]');
		$mail_body->setCols(80);
		$mail_body->setRows(15);
		$mail_body->setValue($this->origin->properties()->get(UserOriginProperties::PASSWORD_MAIL_BODY));
		$send_password->addSubItem($mail_body);
		$mail_date_format = new \ilTextInputGUI($this->pl->txt('usr_prop_password_mail_date_format'), $this->prop(UserOriginProperties::PASSWORD_MAIL_DATE_FORMAT));
		$mail_date_format->setInfo('<a target=\'_blank\' href=\'http://php.net/manual/de/function.date.php\'>Info</a>');
		$mail_date_format->setValue($this->origin->properties()->get(UserOriginProperties::PASSWORD_MAIL_DATE_FORMAT));
		$send_password->addSubItem($mail_date_format);
		$this->addItem($send_password);

		parent::addPropertiesNew();
	}


	protected function addPropertiesUpdate() {
		$activate = new \ilCheckboxInputGUI($this->pl->txt('usr_prop_reactivate_account'), $this->prop(UserOriginProperties::REACTIVATE_ACCOUNT));
		$activate->setInfo($this->pl->txt('usr_prop_reactivate_account_info'));
		$activate->setChecked($this->origin->properties()->get(UserOriginProperties::REACTIVATE_ACCOUNT));
		$this->addItem($activate);

		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new \ilRadioGroupInputGUI($this->pl->txt('usr_prop_delete_mode'), $this->prop(UserOriginProperties::DELETE));
		$opt = new \ilRadioOption($this->pl->txt('usr_prop_delete_mode_none'), UserOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);
		$opt = new \ilRadioOption($this->pl->txt('usr_prop_delete_mode_inactive'), UserOriginProperties::DELETE_MODE_INACTIVE);
		$delete->addOption($opt);
		$opt = new \ilRadioOption($this->pl->txt('usr_prop_delete_mode_delete'), UserOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);
		$delete->setValue($this->origin->properties()->get(UserOriginProperties::DELETE));
		$this->addItem($delete);

		parent::addPropertiesDelete();
	}
}