<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use SRAG\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomyCreate
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 */
class TaxonomyCreate extends AbstractTaxonomy implements ITaxonomyImplementation {

	/**
	 * @inheritDoc
	 */
	public function write() {
		if (!$this->taxonomyExists()) {
			$this->createTaxonomy();
		}

		\ilObjTaxonomy::saveUsage($this->ilObjTaxonomy->getId(), \ilObject2::_lookupObjId($this->getILIASParentId()));
		$this->handleNodes();
	}


	private function createTaxonomy() {
		$tax = new \ilObjTaxonomy();
		$tax->setTitle($this->getTaxonomy()->getTitle());
		$tax->setDescription($this->getTaxonomy()->getDescription());
		$tax->create();
		$tax->createReference();
		$tax->putInTree($this->getILIASParentId());
		$tax->setPermissions($this->getILIASParentId());

		$this->ilObjTaxonomy = $tax;
	}


	protected function handleNodes() {
		$this->initTaxTree();
		foreach ($this->getTaxonomy()->getNodes() as $node) {
			if (!$this->nodeExists($node)) {
				$this->createNode($node);
			}
		}
	}


	/**
	 * @param \SRAG\Plugins\Hub2\Taxonomy\Node\INode $nodeDTO
	 */
	private function createNode(INode $nodeDTO, $parent_id = 0) {
		$node = new \ilTaxonomyNode();
		$node->setTitle($nodeDTO->getTitle());
		$node->setOrderNr(1);
		$node->setTaxonomyId($this->ilObjTaxonomy->getId());
		$node->create();

		if($parent_id == 0){
			\ilTaxonomyNode::putInTree($this->ilObjTaxonomy->getId(), $node, $this->tree_root_id);
			\ilTaxonomyNode::fixOrderNumbers($this->ilObjTaxonomy->getId(), $this->tree_root_id);
		}else{
			\ilTaxonomyNode::putInTree($this->ilObjTaxonomy->getId(), $node, $parent_id);
			\ilTaxonomyNode::fixOrderNumbers($this->ilObjTaxonomy->getId(), $parent_id);
		}

		foreach($nodeDTO->getNodes() as $node_dto){
			$this->createNode($node_dto, $node->getId());
		}
	}
}
