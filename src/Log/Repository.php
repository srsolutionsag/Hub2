<?php

namespace srag\Plugins\Hub2\Log;

use ilDateTime;
use ilDBConstants;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Sync\GlobalHook\GlobalHook;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use stdClass;

/**
 * Class Repository
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements IRepository {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IRepository
	 */
	protected static $instance = null;


	/**
	 * @return IRepository
	 */
	public static function getInstance(): IRepository {
		if (self::$instance === null) {
			self::setInstance(new self());
		}

		return self::$instance;
	}


	/**
	 * @param IRepository $instance
	 */
	public static function setInstance(IRepository $instance)/*: void*/ {
		self::$instance = $instance;
	}


	/**
	 * Additional data which should appear in all logs. E.g. something like
	 * ID of datajunk of delivering system etc.
	 *
	 * @var stdClass
	 */
	protected $global_additional_data;
	/**
	 * @var ILog[][][]
	 */
	protected $kept_logs = [];


	/**
	 * Repository constructor
	 */
	private function __construct() {
		$this->withGlobalAdditionalData(new stdClass());
	}


	/**
	 * @inheritdoc
	 */
	public function deleteLog(ILog $log)/*: void*/ {
		self::dic()->database()->manipulate('DELETE FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME)
			. " WHERE log_id=%s", [ ilDBConstants::T_INTEGER ], [ $log->getLogId() ]);
	}


	/**
	 * @inheritdoc
	 */
	public function deleteOldLogs(int $keep_old_logs_time): int {
		$time = time();
		$keep_old_logs_time_timestamp = ($time - ($keep_old_logs_time * 24 * 60 * 60));
		$keep_old_logs_time_date = new ilDateTime($keep_old_logs_time_timestamp, IL_CAL_UNIX);

		$keep_log_ids = [];
		$result = self::dic()->database()->query('SELECT MAX(log_id) AS log_id FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME) . ' GROUP BY origin_id,object_ext_id');
		while (($row = $result->fetchAssoc()) !== false) {
			$keep_log_ids[] = intval($row["log_id"]);
		}

		$count = self::dic()->database()->manipulateF('DELETE FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME) . ' WHERE date<%s AND ' . self::dic()->database()
				->in("log_id", $keep_log_ids, true, ilDBConstants::T_INTEGER), [ ilDBConstants::T_TEXT ], [ $keep_old_logs_time_date->get(IL_CAL_DATETIME) ]);

		self::dic()->database()->resetAutoIncrement(Log::TABLE_NAME, "log_id");

		return $count;
	}


	/**
	 * @inheritdoc
	 */
	public function factory(): IFactory {
		return Factory::getInstance();
	}


	/**
	 * @inheritdoc
	 */
	public function getLogs(string $sort_by = null, string $sort_by_direction = null, int $limit_start = null, int $limit_end = null, string $title = null, string $message = null, ilDateTime $date_start = null, ilDateTime $date_end = null, int $level = null, int $origin_id = null, string $origin_object_type = null, string $object_ext_id = null, int $object_ilias_id = null, string $additional_data = null): array {

		$sql = 'SELECT *';

		$sql .= $this->getLogsQuery($sort_by, $sort_by_direction, $limit_start, $limit_end, $title, $message, $date_start, $date_end, $level, $origin_id, $origin_object_type, $object_ext_id, $object_ilias_id, $additional_data);

		/**
		 * @var ILog[] $logs
		 */
		$logs = self::dic()->database()->fetchAllCallback(self::dic()->database()->query($sql), [ $this->factory(), "fromDB" ]);

		return $logs;
	}


	/**
	 * @inheritdoc
	 */
	public function getLogsCount(string $title = null, string $message = null, ilDateTime $date_start = null, ilDateTime $date_end = null, int $level = null, int $origin_id = null, string $origin_object_type = null, string $object_ext_id = null, int $object_ilias_id = null, string $additional_data = null): int {

		$sql = 'SELECT COUNT(log_id) AS count';

		$sql .= $this->getLogsQuery(null, null, null, null, $title, $message, $date_start, $date_end, $level, $origin_id, $origin_object_type, $object_ext_id, $object_ilias_id, $additional_data);

		$result = self::dic()->database()->query($sql);

		if (($row = $result->fetchAssoc()) !== false) {
			return intval($row["count"]);
		}

		return 0;
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
	 * @return string
	 */
	private function getLogsQuery(string $sort_by = null, string $sort_by_direction = null, int $limit_start = null, int $limit_end = null, string $title = null, string $message = null, ilDateTime $date_start = null, ilDateTime $date_end = null, int $level = null, int $origin_id = null, string $origin_object_type = null, string $object_ext_id = null, int $object_ilias_id = null, string $additional_data = null): string {

		$sql = ' FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME);

		$wheres = [];

		if (!empty($title)) {
			$wheres[] = self::dic()->database()->like("title", ilDBConstants::T_TEXT, '%' . $title . '%');
		}

		if (!empty($message)) {
			$wheres[] = self::dic()->database()->like("message", ilDBConstants::T_TEXT, '%' . $message . '%');
		}

		if (!empty($date_start)) {
			$wheres[] = 'date>=' . self::dic()->database()->quote($date_start->get(IL_CAL_DATETIME), ilDBConstants::T_TEXT);
		}

		if (!empty($date_end)) {
			$wheres[] = 'date<=' . self::dic()->database()->quote($date_start->get(IL_CAL_DATETIME), ilDBConstants::T_TEXT);
		}

		if (!empty($level)) {
			$wheres[] = 'level=' . self::dic()->database()->quote($level, ilDBConstants::T_INTEGER);
		}

		if (!empty($origin_id)) {
			$wheres[] = 'origin_id=' . self::dic()->database()->quote($origin_id, ilDBConstants::T_INTEGER);
		}

		if (!empty($origin_object_type)) {
			$wheres[] = 'origin_object_type=' . self::dic()->database()->quote($origin_object_type, ilDBConstants::T_TEXT);
		}

		if (!empty($object_ext_id)) {
			$wheres[] = 'object_ext_id LIKE ' . self::dic()->database()->quote($object_ext_id, ilDBConstants::T_TEXT);
		}

		if (!empty($object_ilias_id)) {
			$wheres[] = 'object_ilias_id=' . self::dic()->database()->quote($object_ilias_id, ilDBConstants::T_INTEGER);
		}

		if (!empty($additional_data)) {
			$wheres[] = self::dic()->database()->like("additional_data", ilDBConstants::T_TEXT, '%' . $additional_data . '%');
		}

		if (count($wheres) > 0) {
			$sql .= ' WHERE ' . implode(" AND ", $wheres);
		}

		if ($sort_by !== null && $sort_by_direction !== null) {
			$sql .= ' ORDER BY ' . self::dic()->database()->quoteIdentifier($sort_by) . ' ' . $sort_by_direction;
		}

		if ($limit_start !== null && $limit_end !== null) {
			$sql .= ' LIMIT ' . self::dic()->database()->quote($limit_start, ilDBConstants::T_INTEGER) . ',' . self::dic()->database()->quote($limit_end, ilDBConstants::T_INTEGER);
		}

		return $sql;
	}


	/**
	 * @inheritdoc
	 */
	public function getLogById(int $log_id)/*: ?ILog*/ {
		/**
		 * @var Log|null $log
		 */
		$log = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME)
			. ' WHERE log_id=%s', [ ilDBConstants::T_INTEGER ], [ $log_id ]), [ $this->factory(), "fromDB" ]);

		return $log;
	}


	/**
	 * @inheritdoc
	 */
	public function getGlobalAdditionalData(): stdClass {
		return $this->global_additional_data;
	}


	/**
	 * @inheritdoc
	 */
	public function withGlobalAdditionalData(stdClass $global_additional_data): IRepository {
		$this->global_additional_data = $global_additional_data;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function keepLog(ILog $log)/*:void*/ {
		if (!isset($this->kept_logs[$log->getOriginId()])) {
			$this->kept_logs[$log->getOriginId()] = [];
		}

		if (!isset($this->kept_logs[$log->getOriginId()][$log->getLevel()])) {
			$this->kept_logs[$log->getOriginId()][$log->getLevel()] = [];
		}

		$this->kept_logs[$log->getOriginId()][$log->getLevel()][] = $log;

		GlobalHook::getInstance()->handleLog($log);
	}


	/**
	 * @inheritdoc
	 */
	public function getKeptLogs(IOrigin $origin,/*?*/ int $level = null): array {
		if (!isset($this->kept_logs[$origin->getId()])) {
			return [];
		}

		if ($level === null) {
			return array_reduce($this->kept_logs[$origin->getId()], function (array $logs1, array $logs2): array {
				return array_merge($logs1, $logs2);
			}, []);
		}

		if (isset($this->kept_logs[$origin->getId()][$level])) {
			return $this->kept_logs[$origin->getId()][$level];
		} else {
			return [];
		}
	}


	/**
	 * @inheritdoc
	 */
	public function storeLog(ILog $log)/*: void*/ {
		$date = new ilDateTime(time(), IL_CAL_UNIX);

		if (empty($log->getLogId())) {
			$log->withDate($date);
		}

		$log->withLogId(self::dic()->database()->store(Log::TABLE_NAME, [
			"title" => [ ilDBConstants::T_TEXT, $log->getTitle() ],
			"message" => [ ilDBConstants::T_TEXT, $log->getMessage() ],
			"date" => [ ilDBConstants::T_TEXT, $log->getDate()->get(IL_CAL_DATETIME) ],
			"level" => [ ilDBConstants::T_INTEGER, $log->getLevel() ],
			"additional_data" => [ ilDBConstants::T_TEXT, json_encode($log->getAdditionalData()) ],
			"origin_id" => [ ilDBConstants::T_INTEGER, $log->getOriginId() ],
			"origin_object_type" => [ ilDBConstants::T_TEXT, $log->getOriginObjectType() ],
			"object_ext_id" => [ ilDBConstants::T_TEXT, $log->getObjectExtId() ],
			"object_ilias_id" => [ ilDBConstants::T_INTEGER, $log->getObjectIliasId() ]
		], "log_id", $log->getLogId()));

		$this->keepLog($log);
	}
}
