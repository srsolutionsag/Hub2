<?php

namespace srag\Plugins\Hub2\Origin\Hook;

class Config
{
    protected $all_object_hook = true;

    public function __construct(
        bool $all_object_hook
    ) {
        $this->all_object_hook = $all_object_hook;
    }

    public function hasAllObjectHook() : bool
    {
        return $this->all_object_hook;
    }

    // more to come
}
