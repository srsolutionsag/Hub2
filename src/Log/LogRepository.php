<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Log;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface LogRepository
{
    public function total(array $filter_values): int;

    public function getFiltered(
        array $filter_values,
        int $start,
        int $end,
        string $order_by,
        string $order_direction
    ): array;

    public function purge(int $keep_latest_per_status, int $delete_before_days, ?callable $row_callback = null): int;
}
