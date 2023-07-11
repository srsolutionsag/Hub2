<?php

namespace srag\Plugins\Hub2\Origin\Config\Group;

use srag\Plugins\Hub2\Origin\Config\OriginConfig;

/**
 * Class GroupOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupOriginConfig extends OriginConfig implements IGroupOriginConfig
{
    /**
     * @var array
     */
    protected $courseData
        = [
            self::REF_ID_NO_PARENT_ID_FOUND => 1,
        ];

    public function __construct(array $data)
    {
        parent::__construct(array_merge($this->courseData, $data));
    }

    /**
     * @inheritdoc
     */
    public function getParentRefIdIfNoParentIdFound() : int
    {
        return (int) $this->get(self::REF_ID_NO_PARENT_ID_FOUND);
    }
}
