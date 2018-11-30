<?php

namespace srag\Plugins\Hub2\Logs;

use ilDateTime;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Log\IOriginLog;
use srag\Plugins\Hub2\Log\Log;
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
	 * @param string|null     $sort_by
	 * @param string|null     $sort_by_direction
	 * @param int|null        $log_type
	 * @param string|null     $title
	 * @param string|null     $message
	 * @param ilDateTime|null $date_start
	 * @param ilDateTime|null $date_end
	 * @param int|null        $level
	 * @param int|null        $origin_id
	 * @param string|null     $origin_object_type
	 *
	 * @return array
	 */
	public function getArray(string $sort_by = NULL, string $sort_by_direction = NULL, int $log_type = NULL, string $title = NULL, string $message = NULL, ilDateTime $date_start = NULL, ilDateTime $date_end = NULL, int $level = NULL, int $origin_id = NULL, string $origin_object_type = NULL): array {
		// TODO: Include Log::where([])
		$where = OriginLog::where([]);

		if (!empty($log_type)) {
			$where = $where->where([ "log_type" => $log_type ]);
		}
		if (!empty($title)) {
			$where = $where->where([ "title" => '%' . $title . '%' ], "LIKE");
		}
		if (!empty($message)) {
			$where = $where->where([ "message" => '%' . $message . '%' ], "LIKE");
		}
		if (!empty($date_start)) {
			$where = $where->where([ "date" => $date_start ], ">=");
		}
		if (!empty($date_end)) {
			$where = $where->where([ "date" => $date_end ], "<=");
		}
		if (!empty($level)) {
			$where = $where->where([ "level" => $level ]);
		}
		if (!empty($origin_id)) {
			$where = $where->where([ "origin_id" => $origin_id ]);
		}
		if (!empty($origin_object_type)) {
			$where = $where->where([ "origin_object_type" => $origin_object_type ]);
		}

		if (!empty($sort_by)) {
			$where = $where->orderBy($sort_by, $sort_by_direction);
		}

		return $where->getArray();
	}


	/**
	 * @param int $log_id
	 *
	 * @return Log|null
	 */
	public function getLogById(int $log_id)/*: ?Log*/ {
		/**
		 * @var Log|null $log
		 */

		$log = Log::where([ "log_id" => $log_id ])->first();

		return $log;
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
	public function log(): ILog {
		return (new Log());
	}


	/**
	 * @return ILog
	 */
	public function originLog(IOrigin $orgin): ILog {
		return (new OriginLog())->withOriginId($orgin->getId())->withOriginObjectType($orgin->getObjectType());
	}
}
