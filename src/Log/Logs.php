<?php

namespace srag\Plugins\Hub2\Logs;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Log\IOriginLog;
use srag\Plugins\Hub2\Log\OriginLog;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class Logs
 *
 * @package srag\Plugins\Hub2\Logs
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Logs {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Logs constructor
	 */
	private function __construct() {

	}


	/**
	 * @param int $log_id
	 *
	 * @return IOriginLog|null
	 */
	public function getOriginLogById(int $log_id)/*: ?IOriginLog*/ {
		/**
		 * @var OriginLog|null $log
		 */

		$log = OriginLog::where([ "log_id" => $log_id ])->first();

		return $log;
	}


	/**
	 * @return ILog
	 */
	public function originLog(IOrigin $orgin): ILog {
		return (new OriginLog())->withOriginId($orgin->getId());
	}
}
