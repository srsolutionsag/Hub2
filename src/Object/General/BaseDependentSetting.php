<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\General;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
abstract class BaseDependentSetting implements IDependentSettings
{
    protected array $data = [];

    public function toArray(): array
    {
        return $this->data;
    }

    public function fromArray(array $data): void
    {
        $this->data = $data;
    }

    protected function set(string $key, $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function __unserialize($data): void
    {
        $this->fromArray($data);
    }

    public function __toString(): string
    {
        return (string) $this->__serialize();
    }

    public function fromString(string $data): void
    {
        $this->__unserialize($data);
    }

    public function offsetExists($offset): bool
    {
        return $this->data[$offset] ?? false;
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
    
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }

}
