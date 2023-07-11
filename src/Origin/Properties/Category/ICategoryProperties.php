<?php

namespace srag\Plugins\Hub2\Origin\Properties\Category;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface ICategoryProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICategoryProperties extends IOriginProperties
{
    public const SHOW_INFO_TAB = 'show_info_tab';
    public const SHOW_NEWS = 'show_news';
    public const DELETE_MODE = 'delete_mode';
    public const MOVE_CATEGORY = 'move_category';
    public const DELETE_MODE_MARK_TEXT = 'delete_mode_mark_text';
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_MARK = 1;
    public const DELETE_MODE_DELETE = 2;

    public static function getAvailableDeleteModes() : array;
}
