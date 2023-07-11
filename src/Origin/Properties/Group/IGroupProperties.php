<?php

namespace srag\Plugins\Hub2\Origin\Properties\Group;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IGroupProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupProperties extends IOriginProperties
{
    public const SET_ONLINE = 'set_online';
    public const SET_ONLINE_AGAIN = 'set_online_again';
    public const DELETE_MODE = 'delete_mode';
    public const MOVE_GROUP = 'move_group';
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_CLOSED = 1;
    public const DELETE_MODE_DELETE = 2;
    public const DELETE_MODE_DELETE_OR_CLOSE = 3;
    public const DELETE_MODE_MOVE_TO_TRASH = 4;

    public static function getAvailableDeleteModes() : array;
}
