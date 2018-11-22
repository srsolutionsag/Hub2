<?php

namespace srag\Plugins\Hub2\Log;

use ActiveRecord;
use arConnector;
use ilDateTime;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class Log
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class Log extends ActiveRecord implements ILog {

	use DICTrait;
	use Hub2Trait;
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const TABLE_NAME = "";
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @return string
	 */
	public final function getConnectorContainerName() {
		return static::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public final static function returnDbTableName() {
		return static::TABLE_NAME;
	}


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
	 * @var array
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $additional_data = [];


	/**
	 * Log constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 *
	 * @internal
	 */
	public final function __construct(/*int*/
		$primary_key_value = 0, arConnector $connector = NULL) {
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
				return intval($field_value);

			case "date":
				return new ilDateTime($field_value, IL_CAL_DATETIME);

			case "additional_data":
				return json_decode($field_value);

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
	public function getAdditionalData(): array {
		return $this->additional_data;
	}


	/**
	 * @inheritdoc
	 */
	public function withAdditionalData(array $additional_data): ILog {
		$this->additional_data = $additional_data;

		return $this;
	}
}
