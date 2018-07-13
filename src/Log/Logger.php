<?php

namespace SRAG\Plugins\Hub2\Log;

use ILIAS\Filesystem\Stream\Streams;
use ILIAS\Filesystem\Stream\Stream;

/**
 * Class Logger
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 * @internal
 */
class Logger {
	/**
	 * @var Stream
	 */
	protected $stream;


	/**
	 * Logger constructor.
	 *
	 * @param string $path
	 */
	public function __construct(string $path) {
		global $DIC;

		$this->path = $path;
		if (!$DIC->filesystem()->storage()->has($this->path)) {
			$DIC->filesystem()->storage()->put($this->path, "");
		}

		$resource = fopen(CLIENT_DATA_DIR.'/'.$this->path, 'w');
		$this->stream = Streams::ofResource($resource);

	}


	/**
	 * @param $string
	 */
	public function write($string) {
		$this->stream->seek($this->stream->getSize());
		$this->stream->write($string);
	}
}