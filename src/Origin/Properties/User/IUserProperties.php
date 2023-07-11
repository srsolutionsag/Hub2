<?php

namespace srag\Plugins\Hub2\Origin\Properties\User;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IUserProperties
 * @package srag\Plugins\Hub2\Origin\Properties\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IUserProperties extends IOriginProperties
{
    public const ACTIVATE_ACCOUNT = 'activate_account';
    public const CREATE_PASSWORD = 'create_password';
    public const UPDATE_PASSWORD = 'update_password';
    public const SEND_PASSWORD = 'send_password';
    public const RE_SEND_PASSWORD = 'resend_password';
    public const SEND_PASSWORD_FIELD = 'send_password_field';
    public const PASSWORD_MAIL_SUBJECT = 'password_mail_subject';
    public const PASSWORD_MAIL_BODY = 'password_mail_body';
    public const PASSWORD_MAIL_DATE_FORMAT = 'password_mail_date_format';
    public const REACTIVATE_ACCOUNT = 'reactivate_account';
    public const DELETE = 'delete';
    // How to handle the user if marked as TO_DELETE if data was not delivered
    // Default is "NONE" which means do nothing
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_DELETE = 1;
    public const DELETE_MODE_INACTIVE = 2;

    public static function getAvailableDeleteModes() : array;
}
