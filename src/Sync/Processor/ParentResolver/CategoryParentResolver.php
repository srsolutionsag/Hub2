<?php

namespace srag\Plugins\Hub2\Sync\Processor\ParentResolver;

use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
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
     * @var string|null
     */
    protected $fallback_ext_id = null;
    
    public function __construct(
        int $fallback_ref_id,
        string $fallback_ext_id = null
    ) {
        global $DIC;
        $this->tree = $DIC->repositoryTree();
        $this->fallback_ref_id = $fallback_ref_id;
        $this->fallback_ext_id = $fallback_ext_id;
    }
    
    public function resolveParentRefId(DataTransferObject $dto) : int
    {
        
        
        return $this->checkAndReturnRefId(1);
    }
    
    private function checkAndReturnRefId(int $ref_id) : int
    {
        if (!$this->tree->isInTree($ref_id)) {
            // TODO try to restore
            throw new HubException("Could not find the parent ref-ID in tree: '{$ref_id}'");
        }
        
        return $ref_id;
    }
}
