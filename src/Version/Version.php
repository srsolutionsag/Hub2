<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Version;

/**
 * Class Version
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Version
{
    protected string $version;
    public const GREATER_THAN = ">";
    public const SMALLER_THAN = "<";

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function getVersionString(): string
    {
        return $this->version;
    }

    public function isNewerThan(Version $version): bool
    {
        return version_compare($this->version, $version->getVersionString(), self::GREATER_THAN);
    }

    public function isOlderThan(Version $version): bool
    {
        return version_compare($this->version, $version->getVersionString(), self::SMALLER_THAN);
    }
}
