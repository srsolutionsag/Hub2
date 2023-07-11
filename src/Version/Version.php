<?php

namespace srag\Plugins\Hub2\Version;

/**
 * Class Version
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Version
{
    public const GREATER_THAN = ">";
    public const SMALLER_THAN = "<";

    protected $version = '';

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function getVersionString() : string
    {
        return $this->version;
    }

    public function isNewerThan(Version $version) : bool
    {
        return version_compare($this->version, $version->getVersionString(), self::GREATER_THAN);
    }

    public function isOlderThan(Version $version) : bool
    {
        return version_compare($this->version, $version->getVersionString(), self::SMALLER_THAN);
    }
}
