<?php

namespace srag\Plugins\Hub2\Origin\Session;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Session\SessionOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARSessionOrigin
 * @package srag\Plugins\Hub2\Origin\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionOrigin extends AROrigin implements ISessionOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new SessionOriginConfig($data);
    }


    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new SessionProperties($data);
    }
}
