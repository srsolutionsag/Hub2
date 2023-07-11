<?php

namespace srag\Plugins\Hub2\Origin\Properties\Course;

use srag\Plugins\Hub2\Origin\Properties\OriginProperties;

/**
 * Class CourseProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseProperties extends OriginProperties implements ICourseProperties
{
    /**
     * @var array
     */
    public static $mail_notification_placeholder
        = ['title', 'description', 'responsible', 'notification_email', 'shortlink'];

    /**
     * @inheritdoc
     */
    public static function getPlaceHolderStrings() : string
    {
        $return = '[';
        $return .= implode('], [', self::$mail_notification_placeholder);
        $return .= ']';

        return strtoupper($return);
    }

    /**
     * @var array
     */
    protected $data
        = [
            self::SET_ONLINE => false,
            self::SET_ONLINE_AGAIN => false,
            self::SEND_CREATE_NOTIFICATION => false,
            self::CREATE_NOTIFICATION_SUBJECT => '',
            self::CREATE_NOTIFICATION_BODY => '',
            self::CREATE_NOTIFICATION_FROM => '',
            self::MOVE_COURSE => false,
            self::DELETE_MODE => self::DELETE_MODE_NONE,
        ];

    /**
     * @inheritdoc
     */
    public static function getAvailableDeleteModes() : array
    {
        return [
            self::DELETE_MODE_NONE,
            self::DELETE_MODE_OFFLINE,
            self::DELETE_MODE_DELETE,
            self::DELETE_MODE_DELETE_OR_OFFLINE,
            self::DELETE_MODE_MOVE_TO_TRASH,
        ];
    }
}
