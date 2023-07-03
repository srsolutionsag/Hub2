<?php

namespace srag\Plugins\Hub2\Object\OrgUnit;

use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class OrgUnitDTO
 * @package srag\Plugins\Hub2\Object\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitDTO extends DataTransferObject implements IOrgUnitDTO
{
    use MappingStrategyAwareDataTransferObject;

    /**
     * @var string
     */
    protected $title = "";
    /**
     * @var string
     */
    protected $description = "";
    /**
     * @var int
     */
    protected $owner = 6;
    /**
     * @var string
     */
    protected $parent_id = "";
    /**
     * @var int
     */
    protected $parent_id_type = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var string
     */
    protected $org_unit_type = "";
    /**
     * @var string
     */
    protected $ext_id = "";

    /**
     * @inheritdoc
     */
    public function __construct(string $ext_id)
    {
        parent::__construct($ext_id);
        $this->ext_id = $ext_id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): IOrgUnitDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function setDescription(string $description): IOrgUnitDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @inheritdoc
     */
    public function setOwner(int $owner): IOrgUnitDTO
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentId(): string
    {
        return $this->parent_id;
    }

    /**
     * @inheritdoc
     */
    public function setParentId(string $parent_id): IOrgUnitDTO
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentIdType(): int
    {
        return $this->parent_id_type;
    }

    /**
     * @inheritdoc
     */
    public function setParentIdType(int $parent_id__type): IOrgUnitDTO
    {
        $this->parent_id_type = $parent_id__type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrgUnitType(): string
    {
        return $this->org_unit_type;
    }

    /**
     * @inheritdoc
     */
    public function setOrgUnitType(string $org_unit_type): IOrgUnitDTO
    {
        $this->org_unit_type = $org_unit_type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtId(): string
    {
        return $this->ext_id;
    }

    /**
     * @inheritdoc
     */
    public function setExtId(string $ext_id): IOrgUnitDTO
    {
        $this->ext_id = $ext_id;

        return $this;
    }
}
