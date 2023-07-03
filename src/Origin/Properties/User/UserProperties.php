<?php

namespace srag\Plugins\Hub2\Origin\Properties\User;

use srag\Plugins\Hub2\Origin\Properties\OriginProperties;

/**
 * Class UserProperties
 * @package srag\Plugins\Hub2\Origin\Properties\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserProperties extends OriginProperties implements IUserProperties
{
    /**
     * Default values
     * @var array
     */
    protected $data
        = [
            self::ACTIVATE_ACCOUNT => true,
            self::CREATE_PASSWORD => false,
            self::SEND_PASSWORD => false,
            self::SEND_PASSWORD_FIELD => '',
            self::PASSWORD_MAIL_SUBJECT => '',
            self::PASSWORD_MAIL_BODY => '',
            self::PASSWORD_MAIL_DATE_FORMAT => 'd.m.Y',
            self::REACTIVATE_ACCOUNT => false,
            self::DELETE => self::DELETE_MODE_NONE,
        ];

    /**
     * @inheritdoc
     */
    public static function getAvailableDeleteModes(): array
    {
        return [
            self::DELETE_MODE_NONE,
            self::DELETE_MODE_DELETE,
            self::DELETE_MODE_INACTIVE,
        ];
    }
}
