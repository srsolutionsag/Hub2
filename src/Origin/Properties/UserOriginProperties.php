<?php namespace SRAG\Hub2\Origin\Properties;

/**
 * Class UserOriginProperties
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Properties
 */
class UserOriginProperties extends OriginProperties {

	const ACTIVATE_ACCOUNT = 'activate_account';
	const CREATE_PASSWORD = 'create_password';
	const SEND_PASSWORD = 'send_password';
	const SEND_PASSWORD_FIELD = 'send_password_field';
	const PASSWORD_MAIL_SUBJECT = 'password_mail_subject';
	const PASSWORD_MAIL_BODY = 'password_mail_body';
	const PASSWORD_MAIL_DATE_FORMAT = 'password_mail_date_format';
	const REACTIVATE_ACCOUNT = 'reactivate_account';
	const DELETE = 'delete';
	const LOGIN_FIELD = 'login_field';

	// Which field should be picked to build the login name
	// Default is first letter of firstname and the lastname, separated with a dot
	const LOGIN_FIELD_EMAIL = 'email';
	const LOGIN_FIELD_EXT_ACCOUNT = 'external_account';
	const LOGIN_FIELD_FIRST_LASTNAME = 'first_and_lastname';
	const LOGIN_FIELD_HUB = 'own';
	const LOGIN_FIELD_EXT_ID = 'ext_id';

	// How to handle the user if marked as TO_DELETE if data was not delivered
	// Default is "NONE" which means do nothing
	const DELETE_MODE_DELETE = 1;
	const DELETE_MODE_INACTIVE = 2;

	/**
	 * @return array
	 */
	public static function getAvailableDeleteModes() {
		return [
			self::DELETE_MODE_DELETE,
			self::DELETE_MODE_INACTIVE,
		];
	}

	/**
	 * @return array
	 */
	public static function getAvailableLoginFields() {
		return [
			self::LOGIN_FIELD_EMAIL,
			self::LOGIN_FIELD_EXT_ACCOUNT,
			self::LOGIN_FIELD_EXT_ID,
			self::LOGIN_FIELD_FIRST_LASTNAME, // firstname.lastname
			self::LOGIN_FIELD_HUB // login field of IUser hub object
		];
	}

}