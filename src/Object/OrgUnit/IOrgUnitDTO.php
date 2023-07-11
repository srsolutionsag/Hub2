<?php

namespace srag\Plugins\Hub2\Object\OrgUnit;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IOrgUnitDTO
 * @package srag\Plugins\Hub2\Object\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitDTO extends IDataTransferObject, IMappingStrategyAwareDataTransferObject
{
    /**
     * @var int
     */
    public const PARENT_ID_TYPE_REF_ID = 1;
    /**
     * @var int
     */
    public const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;

    public function getTitle() : string;

    public function setTitle(string $title) : self;

    public function getDescription() : string;

    public function setDescription(string $description) : self;

    public function getOwner() : int;

    public function setOwner(int $owner) : self;

    public function getParentId() : string;

    public function setParentId(string $parent_id) : self;

    public function getParentIdType() : int;

    public function setParentIdType(int $parent_id_type) : self;

    public function getOrgUnitType() : string;

    public function setOrgUnitType(string $org_unit_type) : self;

    public function getExtId() : string;

    public function setExtId(string $ext_id) : self;
}
