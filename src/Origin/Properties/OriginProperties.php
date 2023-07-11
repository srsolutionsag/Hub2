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
    protected array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = array_merge($this->data, $data);
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function updateDTOProperty(string $property): bool
    {
        return (bool) $this->get(self::PREFIX_UPDATE_DTO . $property);
    }

    public function setData(array $data): static
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
