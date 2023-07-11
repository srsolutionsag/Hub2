<?php

namespace srag\Plugins\Hub2\Sync\Processor\ParentResolver;

use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\Course\ICourseDTO;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\IOrigin;

class CourseParentResolver extends BasicParentResolver
{
    /**
     * @var ObjectFactory
     */
    protected $factory;
    /**
     * @var IOrigin
     */
    protected $linked_origin;

    public function __construct(
        int $fallback_ref_id,
        int $linked_origin_id = 0
    ) {
        parent::__construct($fallback_ref_id);
        if ($this->resolveLinkedOrigin($linked_origin_id)) {
            $this->factory = new ObjectFactory($this->linked_origin);
        }
    }

    private function resolveLinkedOrigin(int $linked_origin_id) : bool
    {
        if ($linked_origin_id === 0) {
            return false;
        }

        $originRepository = new OriginRepository();
        $filtered = array_filter(
            $originRepository->categories(),
            function ($origin) use ($linked_origin_id) : bool {
                /** @var IOrigin $origin */
                return $origin->getId() === $linked_origin_id;
            }
        );
        $origin = array_pop($filtered);
        if (!$origin instanceof IOrigin) {
            throw new HubException(
                "The linked origin syncing categories was not found, please check that the correct origin is linked"
            );
        }
        $this->linked_origin = $origin;
        return true;
    }

    public function resolveParentRefId(DataTransferObject $dto) : int
    {
        if (!$dto instanceof CourseDTO) {
            throw new \InvalidArgumentException();
        }

        // Parent ID type is Ref-ID
        if ($dto->getParentIdType() === ICourseDTO::PARENT_ID_TYPE_REF_ID) {
            return $this->resolveRefIdForDTOwithRefIdParentType($dto);
        }

        // Parent ID type is External ID
        if ($dto->getParentIdType() === ICourseDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            if (!$this->linked_origin instanceof IOrigin) {
                throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
            }

            $category = $this->factory->category($dto->getParentId());
            if (!$category->getILIASId()) {
                throw new HubException(
                    "The linked category (" . $category->getExtId() . ") does not (yet) exist in ILIAS for course: "
                    . $dto->getExtId()
                );
            }

            return $this->checkAndReturnRefId($category->getILIASId());
        }

        return $this->checkAndReturnRefId($this->fallback_ref_id);
    }
}
