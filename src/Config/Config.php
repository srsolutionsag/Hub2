<?php

namespace srag\Plugins\Hub2\Config;

use ActiveRecord;
use arConnector;
use LogicException;
use srag\DIC\Hub2\DICTrait;

/**
 * Class Config
 *
 * @package srag\ActiveRecordConfig\Hub2\Config
 */
class Config extends ActiveRecord
{

    /**
     * @var string
     */
    public const SQL_DATE_FORMAT = "Y-m-d H:i:s";
    /**
     * @var int
     */
    public const TYPE_BOOLEAN = 4;
    /**
     * @var int
     */
    public const TYPE_DATETIME = 6;
    /**
     * @var int
     */
    public const TYPE_DOUBLE = 3;
    /**
     * @var int
     */
    public const TYPE_INTEGER = 2;
    /**
     * @var int
     */
    public const TYPE_JSON = 7;
    /**
     * @var int
     */
    public const TYPE_STRING = 1;
    /**
     * @var int
     */
    public const TYPE_TIMESTAMP = 5;
    /**
     * @var string
     */
    protected static $table_name;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_length      100
     * @con_is_notnull  true
     * @con_is_primary  true
     */
    protected $name = "";
    /**
     * @var mixed
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  false
     */
    protected $value;

    /**
     * Config constructor
     *
     * @param string|null      $primary_name_value
     * @param arConnector|null $connector
     */
    public function __construct(/*?string*/ $primary_name_value = null, /*?*/ arConnector $connector = null)
    {
        parent::__construct($primary_name_value, $connector);
    }

    public static function getTableName() : string
    {
        if (empty(self::$table_name)) {
            throw new LogicException("table name is empty - please call repository earlier!");
        }

        return self::$table_name;
    }

    public static function setTableName(string $table_name) : void
    {
        self::$table_name = $table_name;
    }

    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::getTableName();
    }

    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return self::getTableName();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value) : void
    {
        $this->value = $value;
    }
}
