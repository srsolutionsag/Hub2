<?php

namespace srag\Plugins\Hub2\Sync\Summary;

/**
 * Class OriginSyncSummaryFactory
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryFactory implements IOriginSyncSummaryFactory
{
    /**
     * @inheritdoc
     */
    public function web(): IOriginSyncSummary
    {
        return new OriginSyncSummaryWeb();
    }

    /**
     * @inheritdoc
     */
    public function mail(): IOriginSyncSummary
    {
        return new OriginSyncSummaryMail();
    }
}
