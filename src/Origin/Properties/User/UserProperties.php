<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
    protected array $data = [
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

    public static function getAvailableDeleteModes(): array
    {
        return [
            self::DELETE_MODE_NONE,
            self::DELETE_MODE_DELETE,
            self::DELETE_MODE_INACTIVE,
        ];
    }
}
