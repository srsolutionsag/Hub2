<?php

namespace SRAG\Plugins\Hub2\Origin\Properties;

/**
 * Class GroupOriginProperties
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupOriginProperties extends OriginProperties {

	const SET_ONLINE = 'set_online';
	const SET_ONLINE_AGAIN = 'set_online_again';
	const CREATE_ICON = 'create_icon';
	const DELETE_MODE = 'delete_mode';
	const MOVE_GROUP = 'move_group';
	const DELETE_MODE_NONE = 0;
	const DELETE_MODE_CLOSED = 1;
	const DELETE_MODE_DELETE = 2;
	const DELETE_MODE_DELETE_OR_CLOSE = 3;
	const DELETE_MODE_MOVE_TO_TRASH = 4;
	/**
	 * @var array
	 */
	protected $data = [
		self::SET_ONLINE       => false,
		self::SET_ONLINE_AGAIN => false,
		self::CREATE_ICON      => false,
		self::MOVE_GROUP       => false,
		self::DELETE_MODE      => self::DELETE_MODE_NONE,
	];


	/**
	 * @return array
	 */
	public static function getAvailableDeleteModes() {
		return [
			self::DELETE_MODE_NONE,
			self::DELETE_MODE_CLOSED,
			self::DELETE_MODE_DELETE,
			self::DELETE_MODE_DELETE_OR_CLOSE,
			self::DELETE_MODE_MOVE_TO_TRASH,
		];
	}
}