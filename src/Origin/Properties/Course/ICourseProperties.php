<?php

namespace srag\Plugins\Hub2\Origin\Properties\Course;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface ICourseProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseProperties extends IOriginProperties
{
    public const SET_ONLINE = 'set_online';
    public const SET_ONLINE_AGAIN = 'set_online_again';

    public const SEND_CREATE_NOTIFICATION = 'send_create_notification';
    public const CREATE_NOTIFICATION_SUBJECT = 'create_notification_subject';
    public const CREATE_NOTIFICATION_BODY = 'create_notification_body';
    public const CREATE_NOTIFICATION_FROM = 'create_notification_from';
    public const DELETE_MODE = 'delete_mode';
    public const MOVE_COURSE = 'move_course';
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_OFFLINE = 1;
    public const DELETE_MODE_DELETE = 2;
    public const DELETE_MODE_DELETE_OR_OFFLINE = 3; // Set offline if there were any activities in the course, delete otherwise
    public const DELETE_MODE_MOVE_TO_TRASH = 4;

    public static function getPlaceHolderStrings() : string;

    public static function getAvailableDeleteModes() : array;
}
