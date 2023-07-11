<?php

namespace srag\Plugins\Hub2\Taxonomy\Implementation;

use ilObject2;
use ilObjTaxonomy;
use ilRbacLog;
use ilTaxonomyNode;
use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomyCreate
 * @package srag\Plugins\Hub2\Taxonomy\Implementation
 */
class TaxonomyCreate extends AbstractTaxonomy implements ITaxonomyImplementation
{
    /**
     * @var \ilRbacReview
     */
    private $rbacreview;

    public function __construct()
    {
        global $DIC;
        $this->rbacreview = $DIC['rbacreview'];
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function write() : void
    {
        if (!$this->taxonomyExists()) {
            $this->createTaxonomy();
        }

        ilObjTaxonomy::saveUsage($this->ilObjTaxonomy->getId(), ilObject2::_lookupObjId($this->getILIASParentId()));
        $this->handleNodes();
    }

    /**
     *
     */
    private function createTaxonomy() : void
    {
        $tax = new ilObjTaxonomy();
        $tax->setTitle($this->getTaxonomy()->getTitle());
        $tax->setDescription($this->getTaxonomy()->getDescription());
        $tax->create();
        $tax->createReference();
        $tax->putInTree($this->getILIASParentId());
        $tax->setPermissions($this->getILIASParentId());

        // rbac log
        $rbac_log_roles = $this->rbacreview->getParentRoleIds($tax->getRefId(), false);
        $rbac_log = ilRbacLog::gatherFaPa($tax->getRefId(), array_keys($rbac_log_roles), true);
        ilRbacLog::add(ilRbacLog::CREATE_OBJECT, $tax->getRefId(), $rbac_log);

        $this->ilObjTaxonomy = $tax;
    }

    protected function handleNodes()
    {
        $this->initTaxTree();
        foreach ($this->getTaxonomy()->getNodes() as $node) {
            if (!$this->nodeExists($node)) {
                $this->createNode($node);
            }
        }
    }

    private function createNode(INode $nodeDTO, $parent_id = 0) : void
    {
        $node = new ilTaxonomyNode();
        $node->setTitle($nodeDTO->getTitle());
        $node->setOrderNr(1);
        $node->setTaxonomyId($this->ilObjTaxonomy->getId());
        $node->create();

        if ($parent_id == 0) {
            ilTaxonomyNode::putInTree($this->ilObjTaxonomy->getId(), $node, $this->tree_root_id);
            ilTaxonomyNode::fixOrderNumbers($this->ilObjTaxonomy->getId(), $this->tree_root_id);
        } else {
            ilTaxonomyNode::putInTree($this->ilObjTaxonomy->getId(), $node, $parent_id);
            ilTaxonomyNode::fixOrderNumbers($this->ilObjTaxonomy->getId(), $parent_id);
        }

        foreach ($nodeDTO->getNodes() as $node_dto) {
            $this->createNode($node_dto, $node->getId());
        }
    }
}
