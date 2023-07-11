<?php

namespace srag\Plugins\Hub2\Origin\Config\OrgUnit;

use srag\Plugins\Hub2\Origin\Config\OriginConfig;

/**
 * Class OrgUnitOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitOriginConfig extends OriginConfig implements IOrgUnitOriginConfig
{
    /**
     * @var array
     */
    protected $orgUnitConfig
        = [
            self::REF_ID_IF_NO_PARENT_ID => 0,
        ];

    public function __construct(array $data = [])
    {
        parent::__construct(array_merge($this->orgUnitConfig, $data));
    }

    /**
     * @inheritdoc
     */
    public function getRefIdIfNoParentId() : int
    {
        return (int) $this->get(self::REF_ID_IF_NO_PARENT_ID);
    }
}
