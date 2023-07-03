<?php

namespace srag\Plugins\Hub2\Origin\Properties\Session;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface ISessionProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISessionProperties extends IOriginProperties
{
    public const MOVE_SESSION = 'move_session';
    public const DELETE_MODE = 'delete_mode';
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_DELETE = 2;
    public const DELETE_MODE_MOVE_TO_TRASH = 4;
}
