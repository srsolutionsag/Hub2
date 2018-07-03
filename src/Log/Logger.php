<?php

namespace SRAG\Plugins\Hub2\Log;

use SRAG\Plugins\Hub2\Helper\DIC;

/**
 * Class Logger
 *
 * @package SRAG\Plugins\Hub2\Log
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @internal
 */
class Logger {

	use DIC;
	/**
	 * @var string
	 */
	protected $path;


	/**
	 * Logger constructor.
	 *
	 * @param string $path
	 */
	public function __construct(string $path) {
		$this->path = $path;
	}


	/**
	 * @param $string
	 */
	public function write($string) {
		if (!$this->path) {
			return;
		}
		if ($this->filesystem()->storage()->has($this->path)) {
			$this->filesystem()->storage()->put($this->path, $string);
		}
	}
}
