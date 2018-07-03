<?php

namespace SRAG\Plugins\Hub2\Jobs\Result;

/**
 * Class Error
 *
 * @package SRAG\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Error extends AbstractResult {

	protected function initStatus() {
		$this->setStatus(self::STATUS_CRASHED);
	}
}
