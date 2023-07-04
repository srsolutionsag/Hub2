<?php

namespace srag\Plugins\Hub2\Sync\Processor\ParentResolver;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;

interface ParentResolver
{
    public function resolveParentRefId(DataTransferObject $dto): int;

    public function isRefIdDeleted(int $ref_id): bool;

    public function restoreRefId(int $ref_id, int $parent_ref_id): bool;

    public function move(int $ref_id, int $to_ref_id): bool;
}
