<?php

namespace srag\Plugins\Hub2\Log;

use ilDateTime;
use ilDBConstants;
use ilHub2Plugin;
use srag\DIC\Hub2\Exception\DICException;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Sync\GlobalHook\GlobalHook;
use stdClass;

/**
 * Class Repository
 *
 * @package srag\Plugins\Hub2\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements IRepository
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IRepository
     */
    protected static $instance;
    /**
     * @var \ilDBInterface
     */
    protected $db;

    public static function getInstance() : IRepository
    {
        if (self::$instance === null) {
            self::setInstance(new self());
        }

        return self::$instance;
    }

    public static function setInstance(IRepository $instance) : void/*: void*/
    {
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
    private function __construct()
    {
        global $DIC;
        $this->withGlobalAdditionalData(new stdClass());
        $this->db = $DIC->database();
    }

    /**
     * @inheritdoc
     */
    public function deleteLog(ILog $log) : void/*: void*/
    {
        $this->db->manipulateF(
            'DELETE FROM ' . $this->db->quoteIdentifier(Log::TABLE_NAME)
            . " WHERE log_id=%s",
            [ilDBConstants::T_INTEGER],
            [$log->getLogId()]
        );
    }

    /**
     * @inheritdoc
     */
    public function deleteOldLogs(int $keep_old_logs_time) : int
    {
        $time = time();
        $keep_old_logs_time_timestamp = ($time - ($keep_old_logs_time * 24 * 60 * 60));
        $keep_old_logs_time_date = new ilDateTime($keep_old_logs_time_timestamp, IL_CAL_UNIX);

        $keep_log_ids = [];
        $result = $this->db->query(
            'SELECT MAX(log_id) AS log_id FROM '
            . $this->db->quoteIdentifier(Log::TABLE_NAME)
            . ' GROUP BY origin_id,object_ext_id'
        );

        while ($row = $result->fetchAssoc()) {
            $keep_log_ids[] = (int) $row["log_id"];
        }
        // $keep_log_ids = [];
        $count = $this->db->manipulateF(
            'DELETE FROM '
            . $this->db->quoteIdentifier(Log::TABLE_NAME)
            . ' WHERE date<%s AND '
            . $this->db->in(
                "log_id",
                $keep_log_ids,
                true,
                ilDBConstants::T_INTEGER
            ),
            [
                ilDBConstants::T_TEXT
            ],
            [
                $keep_old_logs_time_date->get(IL_CAL_DATETIME)
            ]
        );

        return $count;
    }

    /**
     * @inheritdoc
     */
    public function factory() : IFactory
    {
        return Factory::getInstance();
    }

    /**
     * @inheritdoc
     */
    public function getLogs(
        string $sort_by = null,
        string $sort_by_direction = null,
        int $limit_start = null,
        int $limit_end = null,
        string $title = null,
        string $message = null,
        ilDateTime $date_start = null,
        ilDateTime $date_end = null,
        int $level = null,
        int $origin_id = null,
        string $origin_object_type = null,
        string $object_ext_id = null,
        int $object_ilias_id = null,
        string $additional_data = null,
        int $status = null
    ) : array {
        $sql = 'SELECT *';

        $sql .= $this->getLogsQuery(
            $sort_by,
            $sort_by_direction,
            $limit_start,
            $limit_end,
            $title,
            $message,
            $date_start,
            $date_end,
            $level,
            $origin_id,
            $origin_object_type,
            $object_ext_id,
            $object_ilias_id,
            $additional_data,
            $status
        );

        $stm = $this->db->query($sql);
        $logs = [];
        while ($d = $this->db->fetchObject($stm)) {
            $logs[] = $d;
        }

        return array_map(function (\stdClass $data) : \srag\Plugins\Hub2\Log\ILog {
            return $this->factory()->fromDB($data);
        }, $logs);
    }

    /**
     * @inheritdoc
     */
    public function getLogsCount(
        string $title = null,
        string $message = null,
        ilDateTime $date_start = null,
        ilDateTime $date_end = null,
        int $level = null,
        int $origin_id = null,
        string $origin_object_type = null,
        string $object_ext_id = null,
        int $object_ilias_id = null,
        string $additional_data = null,
        int $status = null
    ) : int {
        $sql = 'SELECT COUNT(log_id) AS count';

        $sql .= $this->getLogsQuery(
            null,
            null,
            null,
            null,
            $title,
            $message,
            $date_start,
            $date_end,
            $level,
            $origin_id,
            $origin_object_type,
            $object_ext_id,
            $object_ilias_id,
            $additional_data,
            $status
        );

        $result = $this->db->query($sql);

        if ($row = $result->fetchAssoc()) {
            return (int) $row["count"];
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
     * @throws DICException
     */
    private function getLogsQuery(
        string $sort_by = null,
        string $sort_by_direction = null,
        int $limit_start = null,
        int $limit_end = null,
        string $title = null,
        string $message = null,
        ilDateTime $date_start = null,
        ilDateTime $date_end = null,
        int $level = null,
        int $origin_id = null,
        string $origin_object_type = null,
        string $object_ext_id = null,
        int $object_ilias_id = null,
        string $additional_data = null,
        int $status = null
    ) : string {
        $sql = ' FROM ' . $this->db->quoteIdentifier(Log::TABLE_NAME);

        $wheres = [];

        if (!empty($title)) {
            $wheres[] = $this->db->like("title", ilDBConstants::T_TEXT, '%' . $title . '%');
        }

        if (!empty($message)) {
            $wheres[] = $this->db->like("message", ilDBConstants::T_TEXT, '%' . $message . '%');
        }

        if ($date_start instanceof \ilDateTime) {
            $wheres[] = 'date>=' . $this->db->quote(
                $date_start->get(IL_CAL_DATETIME),
                ilDBConstants::T_TEXT
            );
        }

        if ($date_end instanceof \ilDateTime) {
            $wheres[] = 'date<=' . $this->db->quote(
                $date_end->get(IL_CAL_DATETIME),
                ilDBConstants::T_TEXT
            );
        }

        if (!empty($level)) {
            $wheres[] = 'level=' . $this->db->quote($level, ilDBConstants::T_INTEGER);
        }

        if (!empty($origin_id)) {
            $wheres[] = 'origin_id=' . $this->db->quote($origin_id, ilDBConstants::T_INTEGER);
        }

        if (!empty($origin_object_type)) {
            $wheres[] = 'origin_object_type=' . $this->db->quote(
                $origin_object_type,
                ilDBConstants::T_TEXT
            );
        }

        if (!empty($object_ext_id)) {
            $wheres[] = 'object_ext_id LIKE ' . $this->db->quote($object_ext_id, ilDBConstants::T_TEXT);
        }

        if (!empty($object_ilias_id)) {
            $wheres[] = 'object_ilias_id=' . $this->db->quote($object_ilias_id, ilDBConstants::T_INTEGER);
        }

        if (!empty($additional_data)) {
            $wheres[] = $this->db->like(
                "additional_data",
                ilDBConstants::T_TEXT,
                '%' . $additional_data . '%'
            );
        }

        if (!empty($status)) {
            $wheres[] = 'status=' . $this->db->quote($status, ilDBConstants::T_INTEGER);
        }

        if ($wheres !== []) {
            $sql .= ' WHERE ' . implode(" AND ", $wheres);
        }

        if ($sort_by !== null && $sort_by_direction !== null) {
            $sql .= ' ORDER BY ' . $this->db->quoteIdentifier($sort_by) . ' ' . $sort_by_direction;
        }

        if ($limit_start !== null && $limit_end !== null) {
            $sql .= ' LIMIT ' . $this->db->quote(
                $limit_start,
                ilDBConstants::T_INTEGER
            ) . ',' . $this->db->quote(
                $limit_end,
                ilDBConstants::T_INTEGER
            );
        }

        return $sql;
    }

    /**
     * @inheritdoc
     */
    public function getLogById(int $log_id)/*: ?ILog*/
    {
        /**
         * @var Log|null $log
         */
        $log = $this->db->fetchObjectCallback(
            $this->db->queryF(
                'SELECT * FROM ' . $this->db->quoteIdentifier(Log::TABLE_NAME)
                . ' WHERE log_id=%s',
                [ilDBConstants::T_INTEGER],
                [$log_id]
            ),
            function (\stdClass $data) : \srag\Plugins\Hub2\Log\ILog {
                return $this->factory()->fromDB($data);
            }
        );

        return $log;
    }

    /**
     * @inheritdoc
     */
    public function getGlobalAdditionalData() : stdClass
    {
        return $this->global_additional_data;
    }

    /**
     * @inheritdoc
     */
    public function withGlobalAdditionalData(stdClass $global_additional_data) : IRepository
    {
        $this->global_additional_data = $global_additional_data;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function keepLog(ILog $log) : void/*:void*/
    {
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
    public function getKeptLogs(IOrigin $origin, /*?*/ int $level = null) : array
    {
        if (!isset($this->kept_logs[$origin->getId()])) {
            return [];
        }

        if ($level === null) {
            return array_reduce(
                $this->kept_logs[$origin->getId()],
                function (array $logs1, array $logs2) : array {
                    return array_merge($logs1, $logs2);
                },
                []
            );
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
    public function storeLog(ILog $log) : void/*: void*/
    {
        $date = new ilDateTime(time(), IL_CAL_UNIX);

        if (empty($log->getLogId())) {
            $log->withDate($date);
        }

        $json_encode = json_encode($log->getAdditionalData(), JSON_THROW_ON_ERROR) ?? '{}';
        $log->withLogId(
            $this->store(
                Log::TABLE_NAME,
                [
                    "title" => [ilDBConstants::T_TEXT, $log->getTitle()],
                    "message" => [ilDBConstants::T_TEXT, $log->getMessage()],
                    "date" => [ilDBConstants::T_TEXT, $log->getDate()->get(IL_CAL_DATETIME)],
                    "level" => [ilDBConstants::T_INTEGER, $log->getLevel()],
                    "additional_data" => [ilDBConstants::T_TEXT, $json_encode],
                    "origin_id" => [ilDBConstants::T_INTEGER, $log->getOriginId()],
                    "origin_object_type" => [ilDBConstants::T_TEXT, $log->getOriginObjectType()],
                    "object_ext_id" => [ilDBConstants::T_TEXT, $log->getObjectExtId()],
                    "object_ilias_id" => [ilDBConstants::T_INTEGER, $log->getObjectIliasId()],
                    "status" => [ilDBConstants::T_INTEGER, $log->getStatus()],
                ],
                "log_id",
                $log->getLogId()
            )
        );

        $this->keepLog($log);
    }

    private function store(
        string $table_name,
        array $values,
        string $primary_key_field,/*?*/
        int $primary_key_value = 0
    ) : int {
        if (empty($primary_key_value)) {
            $this->db->insert($table_name, $values);

            return $this->db->getLastInsertId();
        } else {
            $this->db->update($table_name, $values, [
                $primary_key_field => [ilDBConstants::T_INTEGER, $primary_key_value]
            ]);

            return $primary_key_value;
        }
    }
}
