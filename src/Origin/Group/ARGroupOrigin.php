<?php

namespace srag\Plugins\Hub2\Origin\Group;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Group\GroupOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties;

/**
 * Class ARGroupOrigin
 * @package srag\Plugins\Hub2\Origin\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupOrigin extends AROrigin implements IGroupOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data) : \srag\Plugins\Hub2\Origin\Config\Group\GroupOriginConfig
    {
        return new GroupOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data) : \srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties
    {
        return new GroupProperties($data);
    }
}
