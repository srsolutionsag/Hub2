<?php

namespace srag\Plugins\Hub2\Log\Old;

use ilHub2Plugin;
use ILIAS\Filesystem\Exception\IOException;
use ILIAS\Filesystem\Stream\Stream;
use ILIAS\Filesystem\Stream\Streams;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class LoggerOld
 *
 * @package srag\ILIAS\Plugins\Log\Old
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @internal
 *
 * @deprecated
 */
class LoggerOld {

	use DICTrait;
	use Hub2Trait;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	protected $path;
	/**
	 * @var Stream
	 *
	 * @deprecated
	 */
	protected $stream;


	/**
	 * LoggerOld constructor
	 *
	 * @param string $path
	 *
	 * @throws IOException
	 *
	 * @deprecated
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
	 *
	 * @deprecated
	 */
	public function write($string) {
		$this->stream->seek($this->stream->getSize());
		$this->stream->write($string);
	}
}
