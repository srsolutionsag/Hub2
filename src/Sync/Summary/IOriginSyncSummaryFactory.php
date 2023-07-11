<?php

namespace srag\Plugins\Hub2\Sync\Summary;

/**
 * Interface IOriginSyncSummaryFactory
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummaryFactory
{
    public function web() : IOriginSyncSummary;

    public function mail() : IOriginSyncSummary;
}
