<?php namespace SRAG\Hub2\Log;

/**
 * Interface ILog
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Log
 */
interface ILog {

	// @see ilLogLevel
	const LEVEL_INFO = 200;
	const LEVEL_WARNING = 300;
	const LEVEL_CRITICAL = 500;


	/**
	 * @param string $message
	 * @param int    $level
	 *
	 * @return mixed
	 */
	public function write($message, $level = self::LEVEL_INFO);
}