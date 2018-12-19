<?php

namespace srag\Plugins\Hub2\Log;

use ilHub2Plugin;
use ILIAS\Filesystem\Exception\IOException;
use ILIAS\Filesystem\Stream\Stream;
use ILIAS\Filesystem\Stream\Streams;
use srag\DIC\DICTrait;

/**
 * Class Logger
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 * @internal
 */
class Logger {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var string
	 */
	protected $path;
	/**
	 * @var Stream
	 */
	protected $stream;


	/**
	 * Logger constructor.
	 *
	 * @param string $path
	 *
	 * @throws IOException
	 */
	public function __construct(string $path) {
		$this->path = $path;
		if (!self::dic()->filesystem()->storage()->has($this->path)) {
			self::dic()->filesystem()->storage()->put($this->path, "");
		}

		$resource = fopen(CLIENT_DATA_DIR . '/' . $this->path, 'w');
		$this->stream = Streams::ofResource($resource);
	}


	/**
	 * @param string $string
	 */
	public function write($string) {
		$this->stream->seek($this->stream->getSize());
		$this->stream->write($string);
	}
}
