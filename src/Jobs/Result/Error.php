<?php

namespace SRAG\Plugins\Hub2\Jobs\Result;

/**
 * Class Error
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class Error extends AbstractResult {

	protected function initStatus() {
		$this->setStatus(self::STATUS_CRASHED);
	}
}
