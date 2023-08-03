<?php

namespace srag\Plugins\Hub2\Origin\Properties;

/**
 * Class OriginProperties
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class OriginProperties implements IOriginProperties
{
    /**
     * @var array
     */
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function updateDTOProperty($property)
    {
        return $this->get(self::PREFIX_UPDATE_DTO . $property);
    }

    public function setData(array $data): IOriginProperties
    {
        $this->data = array_merge($this->data, $data);
        
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }
}
