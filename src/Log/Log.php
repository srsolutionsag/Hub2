<?php

namespace srag\Plugins\Hub2\Log;

use ActiveRecord;
use arConnector;
use ilDateTime;
use ilHub2Plugin;
use stdClass;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class Log
 * @package srag\Plugins\Hub2\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Log extends ActiveRecord implements ILog
{
    public const TABLE_NAME = "sr_hub2_log";
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IRepository
     */
    protected $log_repo;

    final public function getConnectorContainerName() : string
    {
        return static::TABLE_NAME;
    }

    /**
     * @deprecated
     */
    final public static function returnDbTableName() : string
    {
        return static::TABLE_NAME;
    }

    /**
     * @var array
     */
    public static $levels
        = [
            self::LEVEL_INFO => self::LEVEL_INFO,
            self::LEVEL_WARNING => self::LEVEL_WARNING,
            self::LEVEL_EXCEPTION => self::LEVEL_EXCEPTION,
            self::LEVEL_CRITICAL => self::LEVEL_CRITICAL,
        ];
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     */
    protected $log_id = 0;
    /**
     * @var string
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $title = "";
    /**
     * @var string
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $message = "";
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     */
    protected $status = 0;
    /**
     * @var ilDateTime
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     */
    protected $date;
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $level = self::LEVEL_INFO;
    /**
     * @var stdClass
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $additional_data;
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $origin_id;
    /**
     * @var string
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $origin_object_type = "";
    /**
     * @var string|null
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       255
     * @con_is_notnull   false
     */
    protected $object_ext_id;
    /**
     * @var int|null
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $object_ilias_id;

    /**
     * Log constructor
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    final public function __construct()
    {
        $this->additional_data = new stdClass();
        $this->log_repo = LogRepository::getInstance();
        //parent::__construct($primary_key_value, $connector);
    }

    /**
     * @inheritdoc
     */
    public function getLogId() : int
    {
        return $this->log_id;
    }

    /**
     * @inheritdoc
     */
    public function withLogId(int $log_id) : ILog
    {
        $this->log_id = $log_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function withTitle(string $title) : ILog
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @inheritdoc
     */
    public function withMessage(string $message) : ILog
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDate() : ilDateTime
    {
        return $this->date;
    }

    /**
     * @inheritdoc
     */
    public function withDate(ilDateTime $date) : ILog
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * @return $this
     */
    public function withStatus(int $status) : ILog
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLevel() : int
    {
        return $this->level;
    }

    /**
     * @inheritdoc
     */
    public function withLevel(int $level) : ILog
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalData() : stdClass
    {
        return $this->additional_data;
    }

    /**
     * @inheritdoc
     */
    public function withAdditionalData(stdClass $additional_data) : ILog
    {
        $this->additional_data = $additional_data;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addAdditionalData(string $key, $value) : ILog
    {
        $this->additional_data->{$key} = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOriginId() : int
    {
        return $this->origin_id;
    }

    /**
     * @inheritdoc
     */
    public function withOriginId(int $origin_id) : ILog
    {
        $this->origin_id = $origin_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOriginObjectType() : string
    {
        return $this->origin_object_type;
    }

    /**
     * @inheritdoc
     */
    public function withOriginObjectType(string $origin_object_type) : ILog
    {
        $this->origin_object_type = $origin_object_type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getObjectExtId()/*: ?string*/
    {
        return $this->object_ext_id;
    }

    /**
     * @inheritdoc
     */
    public function withObjectExtId(/*?*/
        string $object_ext_id = null
    ) : ILog {
        $this->object_ext_id = $object_ext_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getObjectIliasId()/*: ?int*/
    {
        return $this->object_ilias_id;
    }

    /**
     * @inheritdoc
     */
    public function withObjectIliasId(/*?*/
        int $object_ilias_id = null
    ) : ILog {
        $this->object_ilias_id = $object_ilias_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function write(string $message, int $level = self::LEVEL_INFO) : void/*: void*/
    {
        $this->log_repo->storeLog($this->withMessage($message)->withLevel($level));
    }
}
