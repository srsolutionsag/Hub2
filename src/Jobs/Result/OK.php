<?php

namespace SRAG\Plugins\Hub2\Jobs\Result;

/**
 * Class OK
 *
 * @package SRAG\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OK extends AbstractResult {

	protected function initStatus() {
		$this->setStatus(self::STATUS_OK);
	}
}
