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
interface IDependentSettings extends \ArrayAccess, \JsonSerializable
{
    public function toArray(): array;

    public function fromArray(array $data): void;

    public function __toString(): string;

    public function fromString(string $data): void;

    public function __serialize();

    public function __unserialize($data): void;

}
