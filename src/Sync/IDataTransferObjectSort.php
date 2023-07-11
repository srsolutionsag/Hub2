<?php

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

    public function getDtoObject() : IDataTransferObject;

    public function getLevel() : int;

    public function setLevel(int $level);
}
