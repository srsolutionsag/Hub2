<?php

namespace srag\Plugins\Hub2\Origin\Config\CompetenceManagement;

use srag\Plugins\Hub2\Origin\Config\OriginConfig;

/**
 * Class CompetenceManagementOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CompetenceManagementOriginConfig extends OriginConfig implements ICompetenceManagementOriginConfig
{
    /**
     * @var array
     */
    protected $competenceManagementConfig
        = [
            self::ID_IF_NO_PARENT_ID => 0,
        ];

    public function __construct(array $data = [])
    {
        parent::__construct(array_merge($this->competenceManagementConfig, $data));
    }

    /**
     * @inheritdoc
     */
    public function getIdIfNoParentId() : int
    {
        return (int) $this->get(self::ID_IF_NO_PARENT_ID);
    }
}
