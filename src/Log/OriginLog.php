<?php namespace SRAG\ILIAS\Plugins\Log;

use SRAG\ILIAS\Plugins\Hub2\Origin\IOrigin;

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
	 * @param IOrigin $origin
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
	}

	/**
	 * @param string $message
	 * @param int $level
	 * @return mixed
	 */
	public function write($message, $level = self::LEVEL_INFO) {
		// TODO: Implement write() method.
	}
}