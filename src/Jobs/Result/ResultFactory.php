<?php

namespace srag\Plugins\Hub2\Jobs\Result;

/**
 * Class AbstractResult
 *
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ResultFactory {

	/**
	 * @param string $message
	 *
	 * @return OK
	 */
	public static function ok($message) {
		return new OK($message);
	}


	/**
	 * @param string $message
	 *
	 * @return Error
	 */
	public static function error($message) {
		return new Error($message);
	}
}
