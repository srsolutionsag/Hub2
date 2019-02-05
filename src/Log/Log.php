<?php

namespace srag\Plugins\Hub2\Log;

use ActiveRecord;
use arConnector;
use ilDateTime;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use stdClass;

/**
 * Class Log
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Log extends ActiveRecord implements ILog {

	use DICTrait;
	use Hub2Trait;
	const TABLE_NAME = "sr_hub2_log";
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @return string
	 */
	public final function getConnectorContainerName(): string {
		return static::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public final static function returnDbTableName(): string {
		return static::TABLE_NAME;
	}


	/**
	 * @var array
	 */
	public static $levels = [
		self::LEVEL_INFO => self::LEVEL_INFO,
		self::LEVEL_WARNING => self::LEVEL_WARNING,
		self::LEVEL_EXCEPTION => self::LEVEL_EXCEPTION,
		self::LEVEL_CRITICAL => self::LEVEL_CRITICAL
	];
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 * @con_is_primary   true
	 * @con_sequence     true
	 */
	protected $log_id = NULL;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $title = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $message = "";
	/**
	 * @var ilDateTime
	 *
	 * @con_has_field    true
	 * @con_fieldtype    timestamp
	 * @con_is_notnull   true
	 */
	protected $date = NULL;
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 */
	protected $level = self::LEVEL_INFO;
	/**
	 * @var stdClass
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $additional_data;
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   false
	 */
	protected $origin_id = NULL;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $origin_object_type = "";
	/**
	 * @var string|null
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       255
	 * @con_is_notnull   false
	 */
	protected $object_ext_id = NULL;
	/**
	 * @var int|null
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   false
	 */
	protected $object_ilias_id = NULL;


	/**
	 * Log constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public final function __construct(/*int*/
		$primary_key_value = 0, arConnector $connector = NULL) {
		$this->additional_data = new stdClass();

		parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|null
	 */
	public function sleep(/*string*/
		$field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			case "date":
				return $field_value->get(IL_CAL_DATETIME);

			case "additional_data":
				return json_encode($field_value);

			default:
				return NULL;
		}
	}


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "log_id":
			case "level":
			case "origin_id":
				return intval($field_value);

			case "date":
				return new ilDateTime($field_value, IL_CAL_DATETIME);

			case "additional_data":
				return json_decode($field_value);

			case "object_ilias_id":
				if ($field_value !== NULL) {
					return intval($field_value);
				} else {
					return NULL;
				}

			default:
				return NULL;
		}
	}


	/**
	 *
	 */
	public function create()/*: void*/ {
		if ($this->date === NULL) {
			$this->date = new ilDateTime(time(), IL_CAL_UNIX);
		}

		parent::create();
	}


	/**
	 * @inheritdoc
	 */
	public function getLogId(): int {
		return $this->log_id;
	}


	/**
	 * @inheritdoc
	 */
	public function withLogId(int $log_id): ILog {
		$this->log_id = $log_id;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @inheritdoc
	 */
	public function withTitle(string $title): ILog {
		$this->title = $title;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getMessage(): string {
		return $this->message;
	}


	/**
	 * @inheritdoc
	 */
	public function withMessage(string $message): ILog {
		$this->message = $message;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getDate(): ilDateTime {
		return $this->date;
	}


	/**
	 * @inheritdoc
	 */
	public function withDate(ilDateTime $date): ILog {
		$this->date = $date;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getLevel(): int {
		return $this->level;
	}


	/**
	 * @inheritdoc
	 */
	public function withLevel(int $level): ILog {
		$this->level = $level;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getAdditionalData(): stdClass {
		return $this->additional_data;
	}


	/**
	 * @inheritdoc
	 */
	public function withAdditionalData(stdClass $additional_data): ILog {
		$this->additional_data = $additional_data;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function addAdditionalData(string $key, $value): ILog {
		$this->additional_data->{$key} = $value;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getOriginId(): int {
		return $this->origin_id;
	}


	/**
	 * @inheritdoc
	 */
	public function withOriginId(int $origin_id): ILog {
		$this->origin_id = $origin_id;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getOriginObjectType(): string {
		return $this->origin_object_type;
	}


	/**
	 * @inheritdoc
	 */
	public function withOriginObjectType(string $origin_object_type): ILog {
		$this->origin_object_type = $origin_object_type;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getObjectExtId()/*: ?string*/ {
		return $this->object_ext_id;
	}


	/**
	 * @inheritdoc
	 */
	public function withObjectExtId(/*?*/
		string $object_ext_id = NULL): ILog {
		$this->object_ext_id = $object_ext_id;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getObjectIliasId()/*: ?int*/ {
		return $this->object_ilias_id;
	}


	/**
	 * @inheritdoc
	 */
	public function withObjectIliasId(/*?*/
		int $object_ilias_id = NULL): ILog {
		$this->object_ilias_id = $object_ilias_id;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function write(string $message, int $level = self::LEVEL_INFO)/*: void*/ {
		$this->withMessage($message)->withLevel($level)->store();
	}


	/**
	 *
	 */
	public function store()/*: void*/ {
		self::logs()->keepLog($this);

		parent::store();
	}
}
