<?php

namespace srag\Plugins\Hub2\Shortlink;

/**
 * Class NullLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class NullLink implements IObjectLink
{
    /**
     * @inheritdoc
     */
    public function doesObjectExist(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isAccessGranted(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedExternalLink(): string
    {
        return "index.php";
    }

    /**
     * @inheritdoc
     */
    public function getAccessDeniedLink(): string
    {
        return "index.php";
    }

    /**
     * @inheritdoc
     */
    public function getNonExistingLink(): string
    {
        return "index.php";
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedInternalLink(): string
    {
        return "index.php";
    }
}
