<?php

namespace srag\Plugins\Hub2\Sync\Processor\ParentResolver;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Exception\HubException;

class BasicParentResolver implements ParentResolver
{
    /**
     * @var \ilTree
     */
    protected $tree;
    /**
     * @var int
     */
    protected $fallback_ref_id = 1;
    /**
     * @var \ilRepUtil
     */
    protected $rep_util;
    /**
     * @var \ilRbacAdmin
     */
    protected $rbacadmin;

    public function __construct(
        int $fallback_ref_id
    ) {
        global $DIC;
        $this->tree = $DIC->repositoryTree();
        $this->rbacadmin = $DIC->rbac()->admin();
        $this->rep_util = new \ilRepUtil();
        $this->fallback_ref_id = $fallback_ref_id;
    }

    public function resolveParentRefId(DataTransferObject $dto): int
    {
        return $this->checkAndReturnRefId(1);
    }

    public function isRefIdDeleted(int $ref_id): bool
    {
        return (bool) $this->tree->isDeleted($ref_id);
    }

    /**
     * @throws HubException
     */
    protected function resolveRefIdForDTOwithRefIdParentType($dto): int
    {
        if ($this->tree->isInTree($dto->getParentId())) {
            // the ref id exists, we can proceed
            return (int) $dto->getParentId();
        }
        // The ref-ID does not exist in the tree, use the fallback parent ref-ID according to the config
        return $this->checkAndReturnRefId($this->fallback_ref_id);
    }

    public function restoreRefId(int $ref_id, int $parent_ref_id): bool
    {
        $node_data = $this->tree->getNodeTreeData($ref_id);
        $deleted_ref_id = (int) -$node_data['tree'];

        // if a parent node of the org unit was deleted, we first have to recover this parent
        if ($deleted_ref_id !== $ref_id) {
            $node_data_deleted_parent = $this->tree->getNodeTreeData($deleted_ref_id);
            $this->rep_util->restoreObjects($node_data_deleted_parent['parent'], [$deleted_ref_id]);
            // then move the actual orgunit
            $this->tree->moveTree($ref_id, $parent_ref_id);
            // then delete the parent again
            $this->tree->moveToTrash($deleted_ref_id);
        } else {
            // recover and move the actual position
            $this->rep_util->restoreObjects($parent_ref_id, [$ref_id]);
        }
        return true;
    }

    public function move(int $ref_id, int $to_ref_id): bool
    {
        if (!$this->tree->isInTree($ref_id)) {
            $this->tree->insertNode($ref_id, $to_ref_id);
        }

        if ($this->isRefIdDeleted($ref_id)) {
            $this->restoreRefId($ref_id, $to_ref_id);
        }

        $old_parent_id = (int) $this->tree->getParentId($ref_id);
        if ($old_parent_id === $to_ref_id) {
            return false;
        }
        if (
            $this->tree->isDeleted($to_ref_id)
            || !$this->tree->isInTree($to_ref_id)
            || $this->tree->isGrandChild($ref_id, $to_ref_id)
        ) {
            $to_ref_id = $this->fallback_ref_id;
        }
        $this->tree->moveTree($ref_id, $to_ref_id);
        $this->rbacadmin->adjustMovedObjectPermissions(
            $ref_id,
            $old_parent_id
        );
        return true;
    }

    protected function checkAndReturnRefId(int $ref_id): int
    {
        if (!$this->tree->isInTree($ref_id)) {
            // TODO try to restore
            throw new HubException("Could not find ref-ID in tree: '{$ref_id}'");
        }

        return $ref_id;
    }
}
