<?php

namespace srag\Plugins\Hub2\Origin\Properties;

/**
 * Class CourseOriginProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseOriginProperties extends OriginProperties {

	const SET_ONLINE = 'set_online';
	const SET_ONLINE_AGAIN = 'set_online_again';
	const CREATE_ICON = 'create_icon';
	const SEND_CREATE_NOTIFICATION = 'send_create_notification';
	const CREATE_NOTIFICATION_SUBJECT = 'create_notification_subject';
	const CREATE_NOTIFICATION_BODY = 'create_notification_body';
	const CREATE_NOTIFICATION_FROM = 'create_notification_from';
	const DELETE_MODE = 'delete_mode';
	const MOVE_COURSE = 'move_course';
	const DELETE_MODE_NONE = 0;
	const DELETE_MODE_OFFLINE = 1;
	const DELETE_MODE_DELETE = 2;
	const DELETE_MODE_DELETE_OR_OFFLINE = 3; // Set offline if there were any activities in the course, delete otherwise
	const DELETE_MODE_MOVE_TO_TRASH = 4;
	/**
	 * @var array
	 */
	public static $mail_notification_placeholder = array(
		'title',
		'description',
		'responsible',
		'notification_email',
		'shortlink',
	);


	/**
	 * @return string
	 */
	public static function getPlaceHolderStrings() {
		$return = '[';
		$return .= implode('], [', self::$mail_notification_placeholder);
		$return .= ']';

		return strtoupper($return);
	}


	/**
	 * @var array
	 */
	protected $data = [
		self::SET_ONLINE => false,
		self::SET_ONLINE_AGAIN => false,
		self::CREATE_ICON => false,
		self::SEND_CREATE_NOTIFICATION => false,
		self::CREATE_NOTIFICATION_SUBJECT => '',
		self::CREATE_NOTIFICATION_BODY => '',
		self::CREATE_NOTIFICATION_FROM => '',
		self::MOVE_COURSE => false,
		self::DELETE_MODE => self::DELETE_MODE_NONE,
	];


	/**
	 * @return array
	 */
	public static function getAvailableDeleteModes() {
		return [
			self::DELETE_MODE_NONE,
			self::DELETE_MODE_OFFLINE,
			self::DELETE_MODE_DELETE,
			self::DELETE_MODE_DELETE_OR_OFFLINE,
			self::DELETE_MODE_MOVE_TO_TRASH,
		];
	}
}
