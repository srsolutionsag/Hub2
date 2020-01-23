<?php

namespace srag\Plugins\Hub2\Origin\Properties\Session;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface ISessionProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISessionProperties extends IOriginProperties
{

    const MOVE_SESSION = 'move_session';
    const DELETE_MODE = 'delete_mode';
    const DELETE_MODE_NONE = 0;
    const DELETE_MODE_DELETE = 2;
    const DELETE_MODE_MOVE_TO_TRASH = 4;
}
