<?php

namespace SRAG\Plugins\Hub2\Log;

/**
 * Interface ILog
 *
 * @package SRAG\Plugins\Hub2\Log
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ILog {

	// @see ilLogLevel
	const LEVEL_INFO = 200;
	const LEVEL_WARNING = 300;
	const LEVEL_CRITICAL = 500;


	/**
	 * @param string $message
	 * @param int    $level
	 */
	public function write($message, $level = self::LEVEL_INFO);
}
