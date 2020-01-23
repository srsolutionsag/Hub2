<?php

namespace srag\Plugins\Hub2\Origin\Properties\Course;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface ICourseProperties
 *
 * @package srag\Plugins\Hub2\Origin\Properties\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseProperties extends IOriginProperties
{

    const SET_ONLINE = 'set_online';
    const SET_ONLINE_AGAIN = 'set_online_again';
    const CREATE_ICON = 'create_icon';
    const SEND_CREATE_NOTIFICATION = 'send_create_notification';
    const CREATE_NOTIFICATION_SUBJECT = 'create_notification_subject';
    const CREATE_NOTIFICATION_BODY = 'create_notification_body';
    const CREATE_NOTIFICATION_FROM = 'create_notification_from';
    const DELETE_MODE = 'delete_mode';
    const MOVE_COURSE = 'move_course';
    const DELETE_MODE_NONE = 0;
    const DELETE_MODE_OFFLINE = 1;
    const DELETE_MODE_DELETE = 2;
    const DELETE_MODE_DELETE_OR_OFFLINE = 3; // Set offline if there were any activities in the course, delete otherwise
    const DELETE_MODE_MOVE_TO_TRASH = 4;


    /**
     * @return string
     */
    public static function getPlaceHolderStrings() : string;


    /**
     * @return array
     */
    public static function getAvailableDeleteModes() : array;
}
