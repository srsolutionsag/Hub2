<?php

namespace srag\Plugins\Hub2\Jobs;

use ilCronJob;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class AbstractJob
 *
 * @package srag\Plugins\Hub2\Jobs
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractJob extends ilCronJob {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @param string $message
	 *
	 * @deprecated
	 */
	protected function log(string $message) {
		self::dic()->log()->write($message);
	}
}
