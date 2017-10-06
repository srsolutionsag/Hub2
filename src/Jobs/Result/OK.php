<?php

namespace SRAG\Hub2\Jobs\Result;

/**
 * Class OK
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class OK extends AbstractResult {

	protected function initStatus() {
		$this->setStatus(self::STATUS_OK);
	}
}
