<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Summary;

/**
 * Class OriginSyncSummaryFactory
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncSummaryFactory implements IOriginSyncSummaryFactory
{
    public function web(): IOriginSyncSummary
    {
        return new OriginSyncSummaryWeb();
    }

    public function mail(): IOriginSyncSummary
    {
        return new OriginSyncSummaryMail();
    }
}
