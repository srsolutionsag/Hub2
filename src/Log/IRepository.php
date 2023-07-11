<?php

namespace srag\Plugins\Hub2\Log;

use ilDateTime;
use srag\Plugins\Hub2\Origin\IOrigin;
use stdClass;

/**
 * Interface IRepository
 * @package srag\Plugins\Hub2\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IRepository
{
    public function deleteLog(ILog $log)/*: void*/ ;

    public function deleteOldLogs(int $keep_old_logs_time) : int;

    public function factory() : IFactory;

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
     * @param int|null        $status
     * @return ILog[]
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
    ) : array;

    /**
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
     * @param int|null        $status
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
    ) : int;

    /**
     * @return ILog|null
     */
    public function getLogById(int $log_id)/*: ?ILog*/ ;

    public function getGlobalAdditionalData() : stdClass;

    public function withGlobalAdditionalData(stdClass $global_additional_data) : self;

    public function keepLog(ILog $log)/*:void*/ ;

    /**
     * @param int|null $level
     * @return ILog[]
     */
    public function getKeptLogs(IOrigin $origin, /*?*/ int $level = null) : array;

    public function storeLog(ILog $log)/*: void*/ ;
}
