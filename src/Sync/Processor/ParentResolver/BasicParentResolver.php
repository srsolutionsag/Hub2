<?php

namespace srag\Plugins\Hub2\Sync\Processor\ParentResolver;

use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;

interface ParentResolver
{
    public function resolveParentRefId(DataTransferObject $dto) : int;
}
