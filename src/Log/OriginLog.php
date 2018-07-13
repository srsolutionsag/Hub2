<?php namespace SRAG\Plugins\Hub2\Log;

use SRAG\Plugins\Hub2\Origin\IOrigin;

/**
 * Class OriginLog
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Log
 */
class OriginLog implements ILog {

	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var Logger
	 */
	protected $log;
	/**
	 * @var array
	 */
	protected static $ilLogInstances = [];


	/**
	 * @param IOrigin $origin
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
		$this->log = $this->getLogInstance($origin);
	}


	/**
	 * @param string $message
	 * @param int    $level
	 */
	public function write($message, $level = self::LEVEL_INFO) {
		$this->log->write($message);
	}


	/**
	 * @param IOrigin $origin
	 *
	 * @return \SRAG\Plugins\Hub2\Log\Logger
	 */
	private function getLogInstance(IOrigin $origin) {
		if (isset(self::$ilLogInstances[$origin->getId()])) {
			return self::$ilLogInstances[$origin->getId()];
		}
		$filename = implode('-', [
			\ilHub2Plugin::PLUGIN_ID,
			'origin',
			$origin->getObjectType(),
			$origin->getId(),
		]);

		$logger = new Logger('hub/' . $filename . '.log');
		self::$ilLogInstances[$origin->getId()] = $logger;

		return $logger;
	}
}
