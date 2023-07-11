<?php

namespace srag\Plugins\Hub2\Origin\Config\Category;

use srag\Plugins\Hub2\Origin\Config\OriginConfig;

/**
 * Class CategoryOriginConfig
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Origin\Config\Category
 */
class CategoryOriginConfig extends OriginConfig implements ICategoryOriginConfig
{
    /**
     * @var array
     */
    protected $categoryData
        = [
            self::REF_ID_NO_PARENT_ID_FOUND => 1,
            self::EXT_ID_NO_PARENT_ID_FOUND => '',
        ];

    public function __construct(array $data)
    {
        parent::__construct(array_merge($this->categoryData, $data));
    }

    /**
     * @inheritdoc
     */
    public function getParentRefIdIfNoParentIdFound() : int
    {
        return (int) $this->get(self::REF_ID_NO_PARENT_ID_FOUND);
    }

    /**
     * @inheritdoc
     */
    public function getExternalParentIdIfNoParentIdFound() : string
    {
        return (string) $this->get(self::EXT_ID_NO_PARENT_ID_FOUND);
    }
}
