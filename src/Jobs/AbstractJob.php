<?php

namespace SRAG\Plugins\Hub2\Jobs;

use ilCronJob;
use SRAG\Plugins\Hub2\Helper\DIC;

/**
 * Class AbstractJob
 *
 * @package SRAG\Plugins\Hub2\Jobs
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractJob extends ilCronJob {

	use DIC;


	/**
	 * @param string $message
	 */
	protected function log($message) {
		$this->ilLog()->write($message);
	}
}
