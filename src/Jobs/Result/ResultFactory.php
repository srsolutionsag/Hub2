<?php

namespace SRAG\Plugins\Hub2\Jobs\Result;

require_once('./Services/Cron/classes/class.ilCronJobResult.php');

/**
 * Class AbstractResult
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ResultFactory {

	/**
	 * @param $message
	 *
	 * @return OK
	 */
	public static function ok($message) {
		return new OK($message);
	}


	/**
	 * @param $message
	 *
	 * @return Error
	 */
	public static function error($message) {
		return new Error($message);
	}
}
