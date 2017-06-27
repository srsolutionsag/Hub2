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
	const USERNAME_MODE = 'username_mode';

	// Which field should be picked to build the login name
	const USERNAME_MODE_SHORTENED_FIRST_LASTNAME = 'shortened_first_and_lastname'; // John Doe => j.doe
	const USERNAME_MODE_EMAIL = 'email';
	const USERNAME_MODE_EXT_ACCOUNT = 'external_account';
	const USERNAME_MODE_FIRST_LASTNAME = 'first_and_lastname'; // John Doe => john.doe
	const USERNAME_MODE_HUB = 'own'; // Login is picked from the login property on the UserDTO object
	const USERNAME_MODE_EXT_ID = 'ext_id';

	// How to handle the user if marked as TO_DELETE if data was not delivered
	// Default is "NONE" which means do nothing
	const DELETE_MODE_NONE = 0;
	const DELETE_MODE_DELETE = 1;
	const DELETE_MODE_INACTIVE = 2;

	/**
	 * Default values
	 *
	 * @var array
	 */
	protected $data = [
		self::ACTIVATE_ACCOUNT => true,
		self::CREATE_PASSWORD => false,
		self::SEND_PASSWORD => false,
		self::SEND_PASSWORD_FIELD => '',
		self::PASSWORD_MAIL_SUBJECT => '',
		self::PASSWORD_MAIL_BODY => '',
		self::PASSWORD_MAIL_DATE_FORMAT => 'd.m.Y',
		self::REACTIVATE_ACCOUNT => false,
		self::USERNAME_MODE => self::USERNAME_MODE_SHORTENED_FIRST_LASTNAME,
		self::DELETE => self::DELETE_MODE_NONE,
	];

	/**
	 * @return array
	 */
	public static function getAvailableDeleteModes() {
		return [
			self::DELETE_MODE_NONE,
			self::DELETE_MODE_DELETE,
			self::DELETE_MODE_INACTIVE,
		];
	}

	/**
	 * @return array
	 */
	public static function getAvailableUsernameModes() {
		return [
			self::USERNAME_MODE_SHORTENED_FIRST_LASTNAME,
			self::USERNAME_MODE_EMAIL, // email
			self::USERNAME_MODE_EXT_ACCOUNT, // external account
			self::USERNAME_MODE_EXT_ID, // external ID
			self::USERNAME_MODE_FIRST_LASTNAME, // firstname.lastname
			self::USERNAME_MODE_HUB // login field of IUser hub object
		];
	}

}