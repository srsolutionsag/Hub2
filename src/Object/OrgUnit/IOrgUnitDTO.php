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

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self;

    /**
     * @return int
     */
    public function getOwner(): int;

    /**
     * @param int $owner
     * @return self
     */
    public function setOwner(int $owner): self;

    /**
     * @return string
     */
    public function getParentId(): string;

    /**
     * @param string $parent_id
     * @return self
     */
    public function setParentId(string $parent_id): self;

    /**
     * @return int
     */
    public function getParentIdType(): int;

    /**
     * @param int $parent_id_type
     * @return self
     */
    public function setParentIdType(int $parent_id_type): self;

    /**
     * @return string
     */
    public function getOrgUnitType(): string;

    /**
     * @param string $org_unit_type
     * @return self
     */
    public function setOrgUnitType(string $org_unit_type): self;

    /**
     * @return string
     */
    public function getExtId(): string;

    /**
     * @param string $ext_id
     * @return self
     */
    public function setExtId(string $ext_id): self;
}
