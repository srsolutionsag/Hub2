<?php namespace SRAG\Hub2\Log;

require_once('./Services/Logging/classes/class.ilLog.php');

/**
 * Interface ILog
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Log
 */
interface ILog {

	const LEVEL_INFO = \ilLogLevel::INFO;
	const LEVEL_WARNING = \ilLogLevel::WARNING;
	const LEVEL_CRITICAL = \ilLogLevel::CRITICAL;

	/**
	 * @param string $message
	 * @param int $level
	 * @return mixed
	 */
	public function write($message, $level = self::LEVEL_INFO);

}