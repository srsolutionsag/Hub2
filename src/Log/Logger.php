<?php

namespace SRAG\Plugins\Hub2\Log;

/**
 * Class Logger
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 * @internal
 */
class Logger {

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
		global $DIC;
		if (!$this->path) {
			return;
		}
		if ($DIC->filesystem()->storage()->has($this->path)) {
			$DIC->filesystem()->storage()->put($this->path, $string);
		}
	}
}
