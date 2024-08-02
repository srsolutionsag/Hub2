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
    protected ILIASVersion $ilias_version;

    public function __construct()
    {
        $this->ilias_version = new ILIASVersion(ILIAS_VERSION_NUMERIC);
    }

    public function storage(): ResourceStorage
    {
        if ($this->ilias_version->isNewerThan(new ILIASVersion('7.999'))) {
            return new ResourceStorage8();
        }

        return new ResourceStorage7();
    }
}
