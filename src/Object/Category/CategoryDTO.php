<?php

namespace srag\Plugins\Hub2\Object\Category;

use InvalidArgumentException;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DidacticTemplateAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAndMetadataAwareDataTransferObject;

/**
 * Class CategoryDTO
 * @package srag\Plugins\Hub2\Object\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryDTO extends DataTransferObject implements ICategoryDTO
{
    use TaxonomyAndMetadataAwareDataTransferObject;
    use MappingStrategyAwareDataTransferObject;
    use DidacticTemplateAwareDataTransferObject;

    private static array $orderTypes
        = [
            self::ORDER_TYPE_TITLE,
            self::ORDER_TYPE_MANUAL,
            self::ORDER_TYPE_ACTIVATION,
            self::ORDER_TYPE_INHERIT,
            self::ORDER_TYPE_CREATION,
        ];
    private static array $orderDirections
        = [
            self::ORDER_DIRECTION_ASC,
            self::ORDER_DIRECTION_DESC,
        ];
    private static array $newItemsPositions
        = [
            self::ORDER_NEW_ITEMS_POSITION_TOP,
            self::ORDER_NEW_ITEMS_POSITION_BOTTOM,
        ];
    private static array $newItemsOrderTypes
        = [
            self::ORDER_NEW_ITEMS_BY_TITLE,
            self::ORDER_NEW_ITEMS_BY_CREATION,
            self::ORDER_NEW_ITEMS_BY_ACTIVATION,
        ];
    private static array $parentIdTypes
        = [
            self::PARENT_ID_TYPE_REF_ID,
            self::PARENT_ID_TYPE_EXTERNAL_EXT_ID,
        ];
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var int
     */
    protected $orderType = self::ORDER_TYPE_TITLE;
    /**
     * @var int
     */
    protected $orderDirection = self::ORDER_DIRECTION_ASC;
    /**
     * @var int
     */
    protected $newItemsPosition = self::ORDER_NEW_ITEMS_POSITION_BOTTOM;
    /**
     * @var int
     */
    protected $newItemsOrderType = self::ORDER_NEW_ITEMS_BY_TITLE;
    /**
     * @var int
     */
    protected $owner = 6;
    /**
     * @var string
     */
    protected $parentId;
    /**
     * @var int
     */
    protected $parentIdType = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var bool
     */
    protected $showNews = true;
    /**
     * @var bool
     */
    protected $showInfoPage = true;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CategoryDTO
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CategoryDTO
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     * @param int $orderType
     * @return CategoryDTO
     */
    public function setOrderType($orderType): self
    {
        if (!in_array($orderType, self::$orderTypes)) {
            throw new InvalidArgumentException("Given '$orderType' is not a valid order type'");
        }
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * @param int $orderDirection
     * @return CategoryDTO
     */
    public function setOrderDirection($orderDirection): self
    {
        if (!in_array($orderDirection, self::$orderDirections)) {
            throw new InvalidArgumentException("Given '$orderDirection' is not a valid order direction'");
        }
        $this->orderDirection = $orderDirection;

        return $this;
    }

    /**
     * @return int
     */
    public function getNewItemsPosition()
    {
        return $this->newItemsPosition;
    }

    /**
     * @param int $newItemsPosition
     * @return CategoryDTO
     */
    public function setNewItemsPosition($newItemsPosition): self
    {
        if (!in_array($newItemsPosition, self::$newItemsPositions)) {
            throw new InvalidArgumentException("Given '$newItemsPosition' is not a valid new items position'");
        }
        $this->newItemsPosition = $newItemsPosition;

        return $this;
    }

    /**
     * @return int
     */
    public function getNewItemsOrderType()
    {
        return $this->newItemsOrderType;
    }

    /**
     * @param int $newItemsOrderType
     * @return CategoryDTO
     */
    public function setNewItemsOrderType($newItemsOrderType): self
    {
        if (!in_array($newItemsOrderType, self::$newItemsOrderTypes)) {
            throw new InvalidArgumentException("Given '$newItemsOrderType' is not a valid new items order type'");
        }
        $this->newItemsOrderType = $newItemsOrderType;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     * @return CategoryDTO
     */
    public function setOwner($owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     * @return $this
     */
    public function setParentId($parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return int
     */
    public function getParentIdType()
    {
        return $this->parentIdType;
    }

    /**
     * @param int $parentIdType
     * @return CategoryDTO
     */
    public function setParentIdType($parentIdType): self
    {
        if (!in_array($parentIdType, self::$parentIdTypes)) {
            throw new InvalidArgumentException("Invalid parentIdType given '$parentIdType'");
        }
        $this->parentIdType = $parentIdType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowNews()
    {
        return $this->showNews;
    }

    /**
     * @param bool $showNews
     * @return CategoryDTO
     */
    public function setShowNews($showNews): self
    {
        $this->showNews = $showNews;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowInfoPage()
    {
        return $this->showInfoPage;
    }

    /**
     * @param bool $showInfoPage
     * @return CategoryDTO
     */
    public function setShowInfoPage($showInfoPage): self
    {
        $this->showInfoPage = $showInfoPage;

        return $this;
    }
}
