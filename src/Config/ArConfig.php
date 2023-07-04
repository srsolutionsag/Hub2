<?php

namespace srag\Plugins\Hub2\Config;

use ilHub2Plugin;

/**
 * Class ArConfig
 * @package srag\Plugins\Hub2\Config
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ArConfig extends \srag\Plugins\Hub2\Config\ActiveRecordConfig
{
    public const TABLE_NAME = 'sr_hub2_config_n';
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const KEY_ORIGIN_IMPLEMENTATION_PATH = 'origin_implementation_path';
    public const KEY_SHORTLINK_OBJECT_NOT_FOUND = 'shortlink_not_found';
    public const KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE = 'shortlink_no_access';
    public const KEY_SHORTLINK_SUCCESS = 'shortlink_success';
    public const KEY_ADMINISTRATE_HUB_ROLE_IDS = 'administrate_hub_role_ids';
    public const KEY_LOCK_ORIGINS_CONFIG = 'lock_origins_config';
    public const KEY_CUSTOM_VIEWS_ACTIVE = 'key_custom_views_active';
    public const KEY_CUSTOM_VIEWS_PATH = 'key_custom_views_path';
    public const KEY_CUSTOM_VIEWS_CLASS = 'key_custom_views_class';
    public const KEY_GLOBAL_HOCK_ACTIVE = 'key_global_hock_active';
    public const KEY_GLOBAL_HOCK_PATH = 'key_global_hock_path';
    public const KEY_GLOBAL_HOCK_CLASS = 'key_global_hock_class';
    public const KEY_KEEP_OLD_LOGS_TIME = "keep_old_logs_time";
    /**
     * @var array
     */
    protected static $fields
        = [
            self::KEY_ORIGIN_IMPLEMENTATION_PATH => [
                self::TYPE_STRING,
                ILIAS_ABSOLUTE_PATH . "/Customizing/global/origins/"
            ],
            self::KEY_SHORTLINK_OBJECT_NOT_FOUND => self::TYPE_STRING,
            self::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE => self::TYPE_STRING,
            self::KEY_SHORTLINK_SUCCESS => self::TYPE_STRING,
            self::KEY_ADMINISTRATE_HUB_ROLE_IDS => [self::TYPE_JSON, [], true],
            self::KEY_LOCK_ORIGINS_CONFIG => self::TYPE_BOOLEAN,
            self::KEY_CUSTOM_VIEWS_ACTIVE => self::TYPE_BOOLEAN,
            self::KEY_CUSTOM_VIEWS_PATH => self::TYPE_STRING,
            self::KEY_CUSTOM_VIEWS_CLASS => self::TYPE_STRING,
            self::KEY_GLOBAL_HOCK_ACTIVE => self::TYPE_BOOLEAN,
            self::KEY_GLOBAL_HOCK_PATH => self::TYPE_STRING,
            self::KEY_GLOBAL_HOCK_CLASS => self::TYPE_STRING,
            self::KEY_KEEP_OLD_LOGS_TIME => [self::TYPE_INTEGER, 7],
        ];
}
