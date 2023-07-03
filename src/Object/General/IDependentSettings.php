<?php

namespace srag\Plugins\Hub2\Object\General;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface IDependentSettings extends \Serializable, \ArrayAccess, \JsonSerializable
{
    public function __toArray(): array;

    public function __fromArray(array $data): void;

    public function __toString(): string;

    public function __fromString(string $data): void;
}
