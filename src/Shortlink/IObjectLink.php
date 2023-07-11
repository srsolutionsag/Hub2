<?php

namespace srag\Plugins\Hub2\Shortlink;

/**
 * Interface IObjectLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObjectLink
{
    public function doesObjectExist() : bool;

    public function isAccessGranted() : bool;

    public function getAccessGrantedExternalLink() : string;

    public function getAccessDeniedLink() : string;

    public function getNonExistingLink() : string;

    public function getAccessGrantedInternalLink() : string;
}
