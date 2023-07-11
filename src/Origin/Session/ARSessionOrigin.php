<?php

namespace srag\Plugins\Hub2\Origin\Session;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Session\SessionOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties;

/**
 * Class ARSessionOrigin
 * @package srag\Plugins\Hub2\Origin\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionOrigin extends AROrigin implements ISessionOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data) : \srag\Plugins\Hub2\Origin\Config\Session\SessionOriginConfig
    {
        return new SessionOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data) : \srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties
    {
        return new SessionProperties($data);
    }
}
