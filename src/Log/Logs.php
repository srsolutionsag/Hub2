<?php

namespace srag\Plugins\Hub2\Logs;

use ilDateTime;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use stdClass;
use Throwable;

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
	 * Additional data which should appear in all logs. E.g. something like
	 * ID of datajunk of delivering system etc.
	 *
	 * @var stdClass
	 */
	protected $global_additional_data;


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
		$this->withGlobalAdditionalData(new stdClass());
	}


	/**
	 * @param string|null     $sort_by
	 * @param string|null     $sort_by_direction
	 * @param int|null        $limit_start
	 * @param int|null        $limit_end
	 * @param string|null     $title
	 * @param string|null     $message
	 * @param ilDateTime|null $date_start
	 * @param ilDateTime|null $date_end
	 * @param int|null        $level
	 * @param int|null        $origin_id
	 * @param string|null     $origin_object_type
	 * @param string|null     $object_ext_id
	 * @param int|null        $object_ilias_id
	 * @param string|null     $additional_data
	 *
	 * @return array
	 */
	public function getLogs(string $sort_by = NULL, string $sort_by_direction = NULL, int $limit_start = NULL, int $limit_end = NULL, string $title = NULL, string $message = NULL, ilDateTime $date_start = NULL, ilDateTime $date_end = NULL, int $level = NULL, int $origin_id = NULL, string $origin_object_type = NULL, string $object_ext_id = NULL, int $object_ilias_id = NULL, string $additional_data = NULL): array {

		$logs = [];

		$where = Log::where([]);

		if (!empty($title)) {
			$where = $where->where([ "title" => '%' . $title . '%' ], "LIKE");
		}
		if (!empty($message)) {
			$where = $where->where([ "message" => '%' . $message . '%' ], "LIKE");
		}
		if (!empty($date_start)) {
			$where = $where->where([ "date" => $date_start->get(IL_CAL_DATETIME) ], ">=");
		}
		if (!empty($date_end)) {
			$where = $where->where([ "date" => $date_end->get(IL_CAL_DATETIME) ], "<=");
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
		if (!empty($object_ext_id)) {
			$where = $where->where([ "object_ext_id" => $object_ext_id ]);
		}
		if (!empty($object_ilias_id)) {
			$where = $where->where([ "object_ilias_id" => $object_ilias_id ]);
		}
		if (!empty($additional_data)) {
			$where = $where->where([ "additional_data" => '%' . $additional_data . '%' ], "LIKE");
		}

		if ($sort_by !== NULL && $sort_by_direction !== NULL) {
			$where = $where->orderBy($sort_by, $sort_by_direction);
		}

		if ($limit_start !== NULL && $limit_end !== NULL) {
			$where = $where->limit($limit_start, $limit_end);
		}

		$array = $where->getArray();

		if ($limit_start !== NULL && $limit_end !== NULL) {
			// Prevent delivier to match data from next table
			$limit_end -= count($array);
		}

		$logs = array_merge($logs, $array);

		return $logs;
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
	 * @param int $keep_old_logs_time
	 *
	 * @return ILog[]
	 */
	public function getOldLogs(int $keep_old_logs_time): array {
		$logs = [];

		$time = time();
		$keep_old_logs_time_timestamp = ($time - ($keep_old_logs_time * 24 * 60 * 60));
		$keep_old_logs_time_date = new ilDateTime($keep_old_logs_time_timestamp, IL_CAL_UNIX);

		$logs = array_merge($logs, Log::where([ "date" => $keep_old_logs_time_date->get(IL_CAL_DATETIME) ], "<")->get());

		return $logs;
	}


	/**
	 * @return ILog
	 */
	public function log(): ILog {
		return (new Log())->withAdditionalData(clone $this->getGlobalAdditionalData());
	}


	/**
	 * @param IOrigin      $orgin
	 * @param IObject|null $object
	 *
	 * @return ILog
	 */
	public function originLog(IOrigin $orgin, IObject $object = NULL): ILog {
		$log = $this->log()->withOriginId($orgin->getId())->withOriginObjectType($orgin->getObjectType());

		if ($object !== NULL) {
			return $log->withObjectExtId($object->getExtId())->withObjectIliasId($object->getILIASId());
		} else {
			return $log;
		}
	}


	/**
	 * @param Throwable $ex
	 * @param IOrigin   $orgin
	 * @param IObject   $object
	 *
	 * @return ILog
	 */
	public function exception(Throwable $ex, IOrigin $orgin, IObject $object): ILog {
		$log = $this->originLog($orgin, $object);

		$log->withLevel(ILog::LEVEL_EXCEPTION);

		$log->withMessage($ex->getMessage());

		return $log;
	}


	/**
	 * @return stdClass
	 */
	public function getGlobalAdditionalData(): stdClass {
		return $this->global_additional_data;
	}


	/**
	 * @param stdClass $global_additional_data
	 *
	 * @return self
	 */
	public function withGlobalAdditionalData(stdClass $global_additional_data): self {
		$this->global_additional_data = $global_additional_data;

		return $this;
	}
}
