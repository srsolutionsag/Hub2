<?php

namespace srag\Plugins\Hub2\Sync\Processor\ParentResolver;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\Category\ICategoryDTO;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\ObjectFactory;

class CategoryParentResolver extends BasicParentResolver
{
    /**
     * @var string|null
     */
    protected $fallback_ext_id;
    /**
     * @var ObjectFactory
     */
    protected $factory;

    public function __construct(
        ObjectFactory $factory,
        int $fallback_ref_id,
        string $fallback_ext_id = null
    ) {
        parent::__construct($fallback_ref_id);
        $this->factory = $factory;
        $this->fallback_ext_id = $fallback_ext_id;
    }

    public function resolveParentRefId(DataTransferObject $dto) : int
    {
        if (!$dto instanceof CategoryDTO) {
            throw new \InvalidArgumentException();
        }

        // Parent ID type is Ref-ID
        if ($dto->getParentIdType() === ICategoryDTO::PARENT_ID_TYPE_REF_ID) {
            return $this->resolveRefIdForDTOwithRefIdParentType($dto);
        }

        // Parent ID type is External ID
        if ($dto->getParentIdType() === ICategoryDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            $parent_category = $this->factory->category($dto->getParentId());
            // Tha parent ext ID equals the base of the sync, fallback ref id is used
            if ($parent_category->getParentId() === $this->fallback_ext_id) {
                return $this->checkAndReturnRefId($this->fallback_ref_id);
            }

            // no parent ref id available
            if ($parent_category->getILIASId() === null || $parent_category->getILIASId() === 0) {
                return $this->checkAndReturnRefId($this->fallback_ref_id);
            } else {
                return $this->checkAndReturnRefId($parent_category->getILIASId());
            }
        }

        return $this->checkAndReturnRefId($this->fallback_ref_id);
    }
}
