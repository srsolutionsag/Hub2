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
 * Interface IOriginSyncSummaryFactory
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummaryFactory
{
    public function web(): IOriginSyncSummary;

    public function mail(): IOriginSyncSummary;
}
