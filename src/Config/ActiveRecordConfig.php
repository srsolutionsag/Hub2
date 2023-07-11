<?php

namespace srag\Plugins\Hub2\Config;

use arConnector;
use srag\Plugins\Hub2\Exception\ActiveRecordConfigException;

/**
 * Class ActiveRecordConfig
 *
 * @package    srag\ActiveRecordConfig\Hub2
 *
 * @author     studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated Please use AbstractRepository instead
 */
class ActiveRecordConfig extends Config
{

    /**
     * @var string
     *
     * @abstract
     *
     * @deprecated
     */
    public const TABLE_NAME = "";
    /**
     * @var array
     *
     * @abstract
     *
     * @deprecated
     */
    protected static $fields = [];

    /**
     * @deprecated
     */
    protected static function config() : ActiveRecordConfigRepository
    {
        return ActiveRecordConfigRepository::getInstance(static::TABLE_NAME, static::$fields);
    }

    /**
     * @param mixed $default_value
     *
     * @return never
     *
     * @throws ActiveRecordConfigException
     *
     * @deprecated
     */
    final protected static function getDefaultValue(string $name, int $type, $default_value)
    {
        throw new ActiveRecordConfigException(
            "getDefaultValue is not supported anymore - please try to use the second parameter in the fields array instead!",
            ActiveRecordConfigException::CODE_INVALID_FIELD
        );
    }

    /**
     *
     * @return mixed
     * @deprecated
     */
    public static function getField(string $name)
    {
        return self::config()->getValue($name);
    }

    /**
     * Get all values
     *
     * @return array [ [ "name" => value ], ... ]
     *
     * @deprecated
     */
    public static function getFields() : array
    {
        return self::config()->getValues();
    }

    /**
     * Remove a field
     *
     * @param string $name Name
     *
     * @deprecated
     */
    public static function removeField(string $name) : void/*: void*/
    {
        self::config()->removeValue($name);
    }

    /**
     * @param mixed $value
     * @deprecated
     */
    public static function setField(string $name, $value) : void/*: void*/
    {
        self::config()->setValue($name, $value);
    }

    /**
     * Set all values
     *
     * @param array $fields        [ [ "name" => value ], ... ]
     * @param bool  $remove_exists Delete all exists name before
     *
     * @deprecated
     */
    public static function setFields(array $fields, bool $remove_exists = false) : void/*: void*/
    {
        self::config()->setValues($fields, $remove_exists);
    }

    /**
     * ActiveRecordConfig constructor
     *
     * @param string|null      $primary_name_value
     * @param arConnector|null $connector
     *
     * @deprecated
     */
    public function __construct(/*?string*/ $primary_name_value = null, /*?*/ arConnector $connector = null)
    {
        self::config();

        parent::__construct($primary_name_value, $connector);
    }
}
