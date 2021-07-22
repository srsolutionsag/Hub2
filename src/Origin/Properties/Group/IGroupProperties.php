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
     * @return array
     */
    public static function getAvailableDeleteModes() : array;
}
