<?php

namespace srag\Plugins\Hub2\Origin\Properties\Category;

use srag\Plugins\Hub2\Origin\Properties\OriginProperties;

/**
 * Class CategoryProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryProperties extends OriginProperties implements ICategoryProperties
{
    /**
     * @var array
     */
    protected $data
        = [
            self::SHOW_INFO_TAB => false,
            self::SHOW_NEWS => false,
            self::MOVE_CATEGORY => false,
            self::DELETE_MODE => self::DELETE_MODE_NONE,
            self::DELETE_MODE_MARK_TEXT => '',
        ];

    /**
     * @inheritdoc
     */
    public static function getAvailableDeleteModes(): array
    {
        return [
            self::DELETE_MODE_NONE,
            self::DELETE_MODE_MARK,
            self::DELETE_MODE_DELETE,
        ];
    }
}
