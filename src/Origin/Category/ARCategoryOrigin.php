<?php

namespace srag\Plugins\Hub2\Origin\Category;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Category\CategoryOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Category\CategoryProperties;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class ARCategoryOrigin
 * @package srag\Plugins\Hub2\Origin\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCategoryOrigin extends AROrigin implements ICategoryOrigin
{
    protected function getOriginConfig(array $data): IOriginConfig
    {
        return new CategoryOriginConfig($data);
    }

    protected function getOriginProperties(array $data): IOriginProperties
    {
        return new CategoryProperties($data);
    }
}
