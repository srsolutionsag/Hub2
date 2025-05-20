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
 * Class ILIASVersion
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class ILIASVersion extends Version
{
    public const ILIAS_SEVEN = '7.0';
    /**
     * @readonly
     */
    private Version $ilias_7;

    public function __construct(string $version)
    {
        parent::__construct($version);
        $this->ilias_7 = new Version(self::ILIAS_SEVEN);
    }

    public function sevenOrNewer(): bool
    {
        return $this->isNewerThan($this->ilias_7);
    }

    public function lessThanSeven(): bool
    {
        return $this->isOlderThan($this->ilias_7);
    }
}
