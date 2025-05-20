<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin\Hook;

class Config
{
    protected bool $all_object_hook = false;
    protected bool $no_longer_hhok = false;

    public function __construct(
        bool $all_object_hook = false,
        bool $no_longer_hhok = false
    ) {
        $this->all_object_hook = $all_object_hook;
        $this->no_longer_hhok = $no_longer_hhok;
    }

    public function hasAllObjectHook(): bool
    {
        return $this->all_object_hook;
    }

    public function hasNoLongerDeliveredObjectHook(): bool
    {
        return $this->no_longer_hhok;
    }

    // more to come
}
