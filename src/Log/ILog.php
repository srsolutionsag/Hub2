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
	const LEVEL_INFO = 200;
	const LEVEL_WARNING = 300;
	const LEVEL_CRITICAL = 500;
	const LOG_TYPE_HUB2 = 1;


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
	 * @return int
	 */
	public function getLogType(): int;


	/**
	 * @param int $log_type
	 *
	 * @return self
	 */
	public function withLogType(int $log_type): self;


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
     * Syntactic sugar for $log->withMessage()->store();
     *
     * @param string $message
     * @return mixed
     */
	public function write(string $message)/*: void*/;
}
