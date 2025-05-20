<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IDataTransferObjectSort
 * @package srag\Plugins\Hub2\Sync
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IDataTransferObjectSort
{
    /**
     * @var int
     */
    public const MAX_LEVEL = 100;

    public function getDtoObject(): IDataTransferObject;

    public function getLevel(): int;

    public function setLevel(int $level);
}
