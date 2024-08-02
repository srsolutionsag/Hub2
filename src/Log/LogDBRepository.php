<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Log;

use function PHPUnit\Framework\matches;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class LogDBRepository implements LogRepository
{
    private const TABLE_NAME = 'sr_hub2_log';

    /**
     * @readonly
     */
    private \ilDBInterface $db;

    public function __construct()
    {
        global $DIC;
        $this->db = $DIC->database();
    }

    private function buildQuery(
        array $filter_values,
        ?int $start = null,
        ?int $end = null,
        ?string $order_by = null,
        ?string $order_direction = null,
        ?bool $count = false
    ): string {
        $query = $count ? 'SELECT COUNT(*) as total FROM ' . self::TABLE_NAME : 'SELECT * FROM ' . self::TABLE_NAME;

        $where = '';

        if ($filter_values !== []) {
            foreach ($filter_values as $column => $value) {
                if (empty($value)) {
                    continue;
                }
                $value = trim($value);
                $value = str_replace('*', '%', $value);

                switch ($column) {
                    case 'object_ext_id':
                    case 'date':
                        $where .= ' AND ' . $column . ' LIKE ' . $this->db->quote('%' . $value . '%', 'text');
                        break;
                    case 'status':
                    case 'object_ilias_id':
                    case 'origin_id':
                    case 'level':
                        $where .= ' AND ' . $column . ' = ' . $this->db->quote((int) $value, 'integer');
                        break;
                }
            }
        }
        if ($where !== '') {
            // cut off the first AND
            $where = substr($where, 4);
            $query .= ' WHERE ' . $where;
        }

        if ($order_by !== null && $order_direction !== null) {
            $query .= " ORDER BY $order_by $order_direction";
        }
        if ($start !== null && $end !== null) {
            $query .= " LIMIT " . $start . ', ' . $end;
        }

        return $query;
    }

    public function total(array $filter_values): int
    {
        $query = $this->buildQuery($filter_values, null, null, null, null, true);
        return (int) $this->db->query($query)->fetchObject()->total;
    }

    public function getFiltered(
        array $filter_values,
        int $start,
        int $end,
        string $order_by,
        string $order_direction
    ): array {
        $query = $this->buildQuery($filter_values, $start, $end, $order_by, $order_direction);
        $res = $this->db->query(
            $query
        );
        $logs = [];
        while ($row = $this->db->fetchAssoc($res)) {
            $logs[] = $row;
        }

        return $logs;
    }

    public function purge(int $keep_latest_per_status, int $delete_before_days, ?callable $row_callback = null): int
    {
        $delete_before = (new \DateTimeImmutable())->sub(new \DateInterval('P' . $delete_before_days . 'D'));
        $removed = $this->db->manipulateF(
            "DELETE FROM sr_hub2_log WHERE date < %s",
            ['date'],
            [$delete_before->format('Y-m-d')]
        );

        $res = $this->db->query(
            "SELECT DISTINCT origin_id, object_ext_id, status FROM sr_hub2_log
            GROUP BY origin_id, object_ext_id, status
            HAVING COUNT(*) > $keep_latest_per_status
            ;"
        );
        while ($row = $this->db->fetchAssoc($res)) {
            $logs_result = $this->db->queryF(
                "SELECT log_id FROM sr_hub2_log WHERE origin_id = %s AND object_ext_id = %s AND status = %s ORDER BY date DESC",
                ['integer', 'text', 'integer'],
                [$row['origin_id'], $row['object_ext_id'], $row['status']]
            );

            if ($logs_result->rowCount() <= $keep_latest_per_status) {
                continue;
            }
            $removed_in_step = 0;
            $index = 0;
            $log_ids_to_delete = [];

            while ($log = $this->db->fetchAssoc($logs_result)) {
                $index++;
                if ($index <= $keep_latest_per_status) {
                    continue;
                }
                $log_ids_to_delete[] = $log['log_id'];
            }

            $q = "DELETE FROM sr_hub2_log WHERE " . $this->db->in('log_id', $log_ids_to_delete, false, 'integer');
            $removed += $removed_in_step = $this->db->manipulate($q);
            if ($row_callback !== null) {
                $row_callback($row, $removed_in_step);
            }
        }

        return $removed;

        // Alternative Version
        $full = true;
        if ($full) {
            $q = "DELETE FROM sr_hub2_log WHERE log_id IN (SELECT log_id
                    FROM (SELECT log_id, ROW_NUMBER() OVER (PARTITION BY origin_id, object_ext_id, status ORDER BY date DESC) AS n
                        FROM) AS x
                    WHERE n > $keep_latest_per_status)";

            return $removed_in_step = $this->db->manipulate($q);
        }
        $step = 1;
        $removed = 0;
        $res = $this->db->query(
            "SELECT DISTINCT origin_id, object_ext_id, status FROM sr_hub2_log
            GROUP BY origin_id, object_ext_id, status
            HAVING COUNT(*) > $keep_latest_per_status
            ;"
        );
        while ($row = $this->db->fetchAssoc($res)) {
            if ($delete_before_days !== -1 && $step === $delete_before_days) {
                return $removed;
            }

            $q = "DELETE FROM sr_hub2_log WHERE log_id IN (SELECT log_id
                    FROM (SELECT log_id, ROW_NUMBER() OVER (PARTITION BY origin_id, object_ext_id, status ORDER BY date DESC) AS n
                        FROM sr_hub2_log WHERE origin_id = %s AND object_ext_id = %s AND status = %s) AS x
                    WHERE n > $keep_latest_per_status)";

            $removed += $removed_in_step = $this->db->manipulateF(
                $q,
                ['integer', 'text', 'integer'],
                [$row['origin_id'], $row['object_ext_id'], $row['status']]
            );

            if ($row_callback !== null) {
                $row_callback($row, $removed_in_step);
            }
            $step++;
        }
        return $removed;
    }

}
