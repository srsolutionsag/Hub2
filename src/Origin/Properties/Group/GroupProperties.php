<?php

namespace srag\Plugins\Hub2\Origin\Properties\Group;

use srag\Plugins\Hub2\Origin\Properties\OriginProperties;

/**
 * Class GroupProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupProperties extends OriginProperties implements IGroupProperties
{
    /**
     * @var array
     */
    protected $data
        = [
            self::SET_ONLINE => false,
            self::SET_ONLINE_AGAIN => false,
            self::MOVE_GROUP => false,
            self::DELETE_MODE => self::DELETE_MODE_NONE,
        ];

    /**
     * @inheritdoc
     */
    public static function getAvailableDeleteModes() : array
    {
        return [
            self::DELETE_MODE_NONE,
            self::DELETE_MODE_CLOSED,
            self::DELETE_MODE_DELETE,
            self::DELETE_MODE_DELETE_OR_CLOSE,
            self::DELETE_MODE_MOVE_TO_TRASH,
        ];
    }
}
