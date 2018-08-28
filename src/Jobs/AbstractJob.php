<?php

namespace SRAG\Plugins\Hub2\Jobs;

use ilCronJob;
use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class AbstractJob
 *
 * @package SRAG\Plugins\Hub2\Jobs
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractJob extends ilCronJob {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @param string $message
	 */
	protected function log($message) {
		self::dic()->log()->write($message);
	}
}
