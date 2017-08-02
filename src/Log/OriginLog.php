<?php namespace SRAG\Hub2\Log;
use SRAG\Hub2\Origin\IOrigin;

/**
 * Class OriginLog
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Log
 */
class OriginLog implements ILog {

	/**
	 * @var IOrigin
	 */
	protected $origin;

	/**
	 * @var \ilLog
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
	 * @param int $level
	 * @return mixed
	 */
	public function write($message, $level = self::LEVEL_INFO) {
		$this->log->write($message, $level);
	}

	/**
	 * @param IOrigin $origin
	 * @return \ilLog
	 */
	private function getLogInstance(IOrigin $origin) {
		if (isset(self::$ilLogInstances[$origin->getId()])) {
			return self::$ilLogInstances[$origin->getId()];
		}
		$fileName = implode('-', [
			'hub2',
			'origin',
			$origin->getObjectType(),
			$origin->getId(),
		]);
		$ilLog = new \ilLog(ILIAS_DATA_DIR, $fileName . '.log');
		self::$ilLogInstances[$origin->getId()] = $ilLog;
		return $ilLog;
	}
}