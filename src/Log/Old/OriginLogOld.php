<?php

namespace srag\Plugins\Hub2\Log\Old;

use ilHub2Plugin;
use ILIAS\Filesystem\Exception\IOException;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class OriginLogOld
 *
 * @package srag\ILIAS\Plugins\Log\Old
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @deprecated
 */
class OriginLogOld implements ILogOld {

	use DICTrait;
	use Hub2Trait;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IOrigin
	 *
	 * @deprecated
	 */
	protected $origin;
	/**
	 * @var LoggerOld
	 *
	 * @deprecated
	 */
	protected $log;
	/**
	 * @var array
	 *
	 * @deprecated
	 */
	protected static $ilLogInstances = [];


	/**
	 * OriginLogOld constructor
	 *
	 * @param IOrigin $origin
	 *
	 * @deprecated
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
		//$this->log = $this->getLogInstance($origin);
	}


	/**
	 * @param string $message
	 * @param int    $level
	 *
	 * @deprecated
	 */
	public function write($message, $level = ILog::LEVEL_INFO) {
		self::logs()->originLog($this->origin)->withMessage($message)->withLevel($level)->store();
		//$this->log->write($message);
	}


	/**
	 * @param IOrigin $origin
	 *
	 * @return LoggerOld
	 * @throws IOException
	 *
	 * @deprecated
	 */
	private function getLogInstance(IOrigin $origin) {
		if (isset(self::$ilLogInstances[$origin->getId()])) {
			return self::$ilLogInstances[$origin->getId()];
		}
		$filename = implode('-', [
			ilHub2Plugin::PLUGIN_ID,
			'origin',
			$origin->getObjectType(),
			$origin->getId(),
		]);

		$logger = new LoggerOld('hub/' . $filename . '.log');
		self::$ilLogInstances[$origin->getId()] = $logger;

		return $logger;
	}
}
