<?php

namespace srag\Plugins\Hub2\Log;

use ilDateTime;
use stdClass;

/**
 * Interface ILog
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ILog {

	// @see ilLogLevel

	/**
	 * @var int
	 */
	const LEVEL_INFO = 200;
	/**
	 * @var int
	 */
	const LEVEL_WARNING = 300;
	/**
	 * @var int
	 */
	const LEVEL_EXCEPTION = 400;
	/**
	 * @var int
	 */
	const LEVEL_CRITICAL = 500;


	/**
	 * @return int
	 */
	public function getLogId(): int;


	/**
	 * @param int $log_id
	 *
	 * @return self
	 */
	public function withLogId(int $log_id): self;


	/**
	 * @return string
	 */
	public function getTitle(): string;


	/**
	 * @param string $title
	 *
	 * @return self
	 */
	public function withTitle(string $title): self;


	/**
	 * @return string
	 */
	public function getMessage(): string;


	/**
	 * @param string $message
	 *
	 * @return self
	 */
	public function withMessage(string $message): self;


	/**
	 * @return ilDateTime
	 */
	public function getDate(): ilDateTime;


	/**
	 * @param ilDateTime $date
	 *
	 * @return self
	 */
	public function withDate(ilDateTime $date): self;


	/**
	 * @return int
	 */
	public function getLevel(): int;


	/**
	 * @param int $level
	 *
	 * @return self
	 */
	public function withLevel(int $level): self;


	/**
	 * @return stdClass
	 */
	public function getAdditionalData(): stdClass;


	/**
	 * @param stdClass $additional_data
	 *
	 * @return self
	 */
	public function withAdditionalData(stdClass $additional_data): self;


	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return self
	 */
	public function addAdditionalData(string $key, $value): self;


	/**
	 * @return int
	 */
	public function getOriginId(): int;


	/**
	 * @param int $origin_id
	 *
	 * @return self
	 */
	public function withOriginId(int $origin_id): self;


	/**
	 * @return string
	 */
	public function getOriginObjectType(): string;


	/**
	 * @param string $origin_object_type
	 *
	 * @return self
	 */
	public function withOriginObjectType(string $origin_object_type): self;


	/**
	 * @return string|null
	 */
	public function getObjectExtId()/*: ?string*/
	;


	/**
	 * @param string|null $object_ext_id
	 *
	 * @return self
	 */
	public function withObjectExtId(/*?*/
		string $object_ext_id = NULL): self;


	/**
	 * @return int|null
	 */
	public function getObjectIliasId()/*: ?int*/
	;


	/**
	 * @param int|null $object_ilias_id
	 *
	 * @return int
	 */
	public function withObjectIliasId(/*?*/
		int $object_ilias_id = NULL): self;


	/**
	 *
	 */
	public function delete()/*: void*/
	;


	/**
	 *
	 */
	public function store()/*: void*/
	;


	/**
	 * Syntactic sugar for $log->withMessage()->withLevel()->store();
	 *
	 * @param string $message
	 * @param int    $level
	 */
	public function write(string $message, int $level = self::LEVEL_INFO)/*: void*/
	;
}
