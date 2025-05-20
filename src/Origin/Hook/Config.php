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
    public function __construct(bool $all_object_hook = false)
    {
        $this->all_object_hook = $all_object_hook;
    }

    public function hasAllObjectHook(): bool
    {
        return $this->all_object_hook;
    }

    // more to come
}
