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

    public function storage() : ResourceStorage
    {
        return new ResourceStorage7();
    }

    public function stakeholder() : \srag\Plugins\Hub2\FileDrop\ResourceStorage\Stakeholder7
    {
        return new Stakeholder7();
    }
}
