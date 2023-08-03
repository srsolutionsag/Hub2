<?php

namespace srag\Plugins\Hub2\Origin\Group;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Group\GroupOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARGroupOrigin
 * @package srag\Plugins\Hub2\Origin\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupOrigin extends AROrigin implements IGroupOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new GroupOriginConfig($data);
    }


    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new GroupProperties($data);
    }
}
