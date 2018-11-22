<?php

namespace srag\Plugins\Hub2\Origin\Properties\Category;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitMembershipOriginProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICategoryProperties extends IOriginProperties {

	const SHOW_INFO_TAB = 'show_info_tab';
	const SHOW_NEWS = 'show_news';
	const DELETE_MODE = 'delete_mode';
	const MOVE_CATEGORY = 'move_category';
	const DELETE_MODE_MARK_TEXT = 'delete_mode_mark_text';
	const DELETE_MODE_NONE = 0;
	const DELETE_MODE_MARK = 1;
	const DELETE_MODE_DELETE = 2;


	/**
	 * @return array
	 */
	public static function getAvailableDeleteModes(): array;
}
