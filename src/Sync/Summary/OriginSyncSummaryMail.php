<?php

namespace srag\Plugins\Hub2\Sync\Summary;

use srag\Plugins\Hub2\Sync\IOriginSync;

/**
 * Class OriginSyncSummaryCron
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryMail extends OriginSyncSummaryBase implements IOriginSyncSummary
{
    /**
     * @inheritdoc
     */
    protected function renderOneSync(
        IOriginSync $originSync,
        bool $only_logs = false,
        bool $output_message = null
    ): string {
        return parent::renderOneSync($originSync, $only_logs, true);
    }
}
