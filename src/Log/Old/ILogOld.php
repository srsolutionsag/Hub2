<?php

namespace srag\Plugins\Hub2\Log\Old;

use srag\Plugins\Hub2\Log\ILog;

/**
 * Interface ILogOld
 *
 * @package srag\ILIAS\Plugins\Log\Old
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @deprecated
 */
interface ILogOld {

	/**
	 * @param string $message
	 * @param int    $level
	 *
	 * @deprecated
	 */
	public function write($message, $level = ILog::LEVEL_INFO);
}
