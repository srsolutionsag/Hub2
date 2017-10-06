<?php

namespace SRAG\Hub2\Jobs;

/**
 * Class AbstractJob
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractJob extends \ilCronJob {

	/**
	 * @param $message
	 */
	protected function log($message) {
		/**
		 * @var $ilLog \ilLog
		 */
		global $ilLog;
		$ilLog->write($message);
	}
}
