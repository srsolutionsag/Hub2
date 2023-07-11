<?php

namespace srag\Plugins\Hub2\Object\DTO;

use ArrayObject;
use ilHub2Plugin;
use Serializable;
use srag\Plugins\Hub2\Sync\Processor\HashCodeComputer;

/**
 * Class ObjectDTO
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class DataTransferObject implements IDataTransferObject
{
    use HashCodeComputer;

    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var string
     */
    private $ext_id = '';
    /**
     * @var string
     */
    private $period = '';
    /**
     * @var bool
     */
    private $should_deleted = false;
    /**
     * @var Serializable
     */
    protected $additionalData;

    /**
     * @param string $ext_id
     */
    public function __construct($ext_id)
    {
        $this->ext_id = $ext_id;
    }

    /**
     * @inheritdoc
     */
    public function getExtId()
    {
        return $this->ext_id;
    }

    /**
     * @inheritdoc
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @inheritdoc
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = [];
        foreach ($this->getProperties() as $key) {
            $this->sleepValue($data, $key);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function setData(array $data)
    {
        foreach (array_keys($data) as $key) {
            if ($key !== "should_deleted") {
                $this->wakeUpValue($data, $key);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getProperties()
    {
        return array_filter(
            array_keys(get_class_vars(get_class($this))),
            function (string $property) : bool {
                return ($property !== "should_deleted");
            }
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(
            ', ',
            [
                "ext_id: " . $this->getExtId(),
                "period: " . $this->getPeriod(),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function shouldDeleted() : bool
    {
        return $this->should_deleted;
    }

    /**
     * @inheritdoc
     */
    public function setShouldDeleted(bool $should_deleted)
    {
        $this->should_deleted = $should_deleted;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalData() : Serializable
    {
        $object = unserialize($this->additionalData);
        if (!$object) {
            return unserialize(serialize(new ArrayObject()));
        }

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function withAdditionalData(Serializable $additionalData)
    {
        $this->additionalData = serialize($additionalData);

        return $this;
    }

    protected function sleepValue(array &$data, string $key)
    {
        $data[$key] = $this->{$key};
    }

    protected function wakeUpValue(array $data, string $key)
    {
        $this->{$key} = $data[$key];
    }
}
