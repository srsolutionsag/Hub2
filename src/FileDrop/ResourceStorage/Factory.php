<?php

namespace srag\Plugins\Hub2\FileDrop\ResourceStorage;

use srag\Plugins\Hub2\Version\ILIASVersion;

/**
 * Class Factory
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Factory
{
    /**
     * @var ILIASVersion
     */
    protected $ilias_version;

    public function __construct()
    {
        $this->ilias_version = new ILIASVersion(ILIAS_VERSION_NUMERIC);
    }

    public function storage(): ResourceStorage
    {
        if ($this->ilias_version->lessThanSeven()) {
            return new ResourceStorage6();
        }
        return new ResourceStorage7();
    }

    public function stakeholder()
    {
        if ($this->ilias_version->lessThanSeven()) {
            return new Stakeholder6();
        }
        return new Stakeholder7();
    }
}
