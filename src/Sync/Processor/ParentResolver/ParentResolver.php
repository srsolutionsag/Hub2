<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor\ParentResolver;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;

interface ParentResolver
{
    public function resolveParentRefId(DataTransferObject $dto): int;

    public function isRefIdDeleted(int $ref_id): bool;

    public function restoreRefId(int $ref_id, int $parent_ref_id): bool;

    public function move(int $ref_id, int $to_ref_id): bool;
}
