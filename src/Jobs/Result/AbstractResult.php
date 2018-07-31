<?php

namespace SRAG\Plugins\Hub2\Jobs\Result;

use ilCronJobResult;

/**
 * Class AbstractResult
 *
 * @package SRAG\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractResult extends ilCronJobResult {

	const STATUS_OK = 3;
	const STATUS_CRASHED = 4;


	/**
	 * AbstractResult constructor.
	 *
	 * @param string $message
	 */
	public function __construct($message) {
		$this->setMessage($message);
		$this->initStatus();
	}


	/**
	 * inits the status to STATUS_OK or STATUS_CRASHED
	 */
	abstract protected function initStatus();
}
