<?php

namespace srag\Plugins\Hub2\Config;

use ActiveRecord;
use ilHub2Plugin;

/**
 * Class ArConfigOld
 * @package srag\Plugins\Hub2\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ArConfigOld extends ActiveRecord
{
    /**
     * @var string
     * @deprecated
     */
    public const TABLE_NAME = 'sr_hub2_config';
    /**
     * @var string
     * @deprecated
     */
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @deprecated
     */
    public function getConnectorContainerName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @deprecated
     */
    public static function returnDbTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       64
     * @db_is_primary   true
     * @var string
     * @deprecated
     */
    protected $identifier;
    /**
     * @db_has_field    true
     * @db_fieldtype    clob
     * @var string
     * @deprecated
     */
    protected $value;

    /**
     * Get a config value by key.
     * @param string $key
     * @return mixed
     * @deprecated
     */
    public static function getValueByKey($key)
    {
        /** @var ARConfig $config */
        $config = self::find($key);

        return ($config) ? $config->getValue() : null;
    }

    /**
     * @param string $key
     * @return ArConfigOld
     * @deprecated
     */
    public static function getInstanceByKey($key)
    {
        $instance = self::find($key);
        if ($instance === null) {
            $instance = new self();
            $instance->setKey($key);
        }

        return $instance;
    }

    /**
     * Encode array data as JSON in database
     * @param string $field_name
     * @return mixed|string
     * @deprecated
     */
    public function sleep($field_name)
    {
        if ($field_name === 'value') {
            return (is_array($this->value)) ? json_encode($this->value, JSON_THROW_ON_ERROR) : $this->value;
        }

        return parent::sleep($field_name);
    }

    /**
     * @return string
     * @deprecated
     */
    public function getKey()
    {
        return $this->identifier;
    }

    /**
     * @param string $key
     * @deprecated
     */
    public function setKey($key): void
    {
        $this->identifier = $key;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @deprecated
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
