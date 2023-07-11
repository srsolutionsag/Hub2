<?php

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class DataTransferObjectSort
 * @package srag\Plugins\Hub2\Sync
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class DataTransferObjectSort implements IDataTransferObjectSort
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IDataTransferObject
     */
    private $dto_object;
    /**
     * @var int
     */
    private $level = 1;

    public function __construct(IDataTransferObject $dto_object)
    {
        $this->dto_object = $dto_object;
    }

    /**
     * @inheritdoc
     */
    public function getDtoObject() : IDataTransferObject
    {
        return $this->dto_object;
    }

    /**
     * @inheritdoc
     */
    public function setDtoObject(IDataTransferObject $dto_object) : void
    {
        $this->dto_object = $dto_object;
    }

    /**
     * @inheritdoc
     */
    public function getLevel() : int
    {
        return $this->level;
    }

    /**
     * @inheritdoc
     */
    public function setLevel(int $level) : void
    {
        $this->level = $level;
    }
}
