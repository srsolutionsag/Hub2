<?php

namespace srag\Plugins\Hub2\Origin\Properties;

/**
 * Class CategoryOriginProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryOriginProperties extends OriginProperties {

	const SHOW_INFO_TAB = 'show_info_tab';
	const SHOW_NEWS = 'show_news';
	const DELETE_MODE = 'delete_mode';
	const MOVE_CATEGORY = 'move_category';
	const DELETE_MODE_MARK_TEXT = 'delete_mode_mark_text';
	const DELETE_MODE_NONE = 0;
	const DELETE_MODE_MARK = 1;
	const DELETE_MODE_DELETE = 2;
	/**
	 * @var array
	 */
	protected $data = [
		self::SHOW_INFO_TAB => false,
		self::SHOW_NEWS => false,
		self::MOVE_CATEGORY => false,
		self::DELETE_MODE => self::DELETE_MODE_NONE,
		self::DELETE_MODE_MARK_TEXT => '',
	];


	/**
	 * @return array
	 */
	public static function getAvailableDeleteModes() {
		return [
			self::DELETE_MODE_NONE,
			self::DELETE_MODE_MARK,
			self::DELETE_MODE_DELETE,
		];
	}
}
