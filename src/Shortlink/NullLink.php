<?php

namespace srag\Plugins\Hub2\Shortlink;

/**
 * Class NullLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class NullLink implements IObjectLink
{
    public function doesObjectExist(): bool
    {
        return false;
    }


    public function isAccessGranted(): bool
    {
        return false;
    }


    public function getAccessGrantedExternalLink(): string
    {
        return "index.php";
    }


    public function getAccessDeniedLink(): string
    {
        return "index.php";
    }


    public function getNonExistingLink(): string
    {
        return "index.php";
    }


    public function getAccessGrantedInternalLink(): string
    {
        return "index.php";
    }
}
