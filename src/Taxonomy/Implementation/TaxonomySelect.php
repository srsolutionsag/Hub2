<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Implementation;

use ilContainer;
use ilObject2;
use ilObjectServiceSettingsGUI;
use ilObjTaxonomy;
use ilTaxNodeAssignment;
use ilTaxonomyException;
use SRAG\Plugins\Hub2\Exception\TaxonomyNodeNotFoundException;
use SRAG\Plugins\Hub2\Exception\TaxonomyNotFoundException;
use SRAG\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomySelect
 *
 * @package SRAG\Plugins\Hub2\Taxonomy\Implementation
 */
class TaxonomySelect extends AbstractTaxonomy implements ITaxonomyImplementation {

	/**
	 * @var int
	 */
	protected $container_obj_id;
	/**
	 * @var ilTaxNodeAssignment
	 */
	protected $ilTaxNodeAssignment;
	/**
	 * @var array
	 */
	protected $selectable_taxonomies = [];


	/**
	 * @inheritDoc
	 */
	public function write() {
		$this->initSelectableTaxonomies();

		if (!$this->taxonomyExists()) {
			throw new TaxonomyNotFoundException($this->getTaxonomy());
		}
		$this->selectTaxonomy();
		$this->handleNodes();
	}


	/**
	 *
	 */
	protected function handleNodes() {
		$this->initTaxTree();
		foreach ($this->getTaxonomy()->getNodes() as $node) {
			if (!$this->nodeExists($node)) {
				throw new TaxonomyNodeNotFoundException($node);
			}
			$this->selectNode($node);
		}
	}


	/**
	 *
	 */
	private function selectTaxonomy() {
		$tax_id = array_search($this->getTaxonomy()->getTitle(), $this->selectable_taxonomies);
		if (!$tax_id) {
			throw new TaxonomyNotFoundException($this->getTaxonomy());
		}
		$this->ilObjTaxonomy = new ilObjTaxonomy($tax_id);
		$this->container_obj_id = ilObject2::_lookupObjId($this->getILIASParentId());
		$a_component_id = ilObject2::_lookupType($this->container_obj_id);
		$this->ilTaxNodeAssignment = new ilTaxNodeAssignment($a_component_id, $this->container_obj_id, "obj", $tax_id);
	}


	/**
	 * @param INode $node
	 *
	 * @throws TaxonomyNodeNotFoundException
	 * @throws ilTaxonomyException
	 */
	private function selectNode(INode $node) {
		$node_id = array_search($node->getTitle(), $this->childs);
		if (!$node_id) {
			throw new TaxonomyNodeNotFoundException($node);
		}

		$this->ilTaxNodeAssignment->addAssignment($node_id, $this->container_obj_id);
	}


	/**
	 * @inheritDoc
	 */
	protected function taxonomyExists(): bool {
		return in_array($this->getTaxonomy()->getTitle(), $this->selectable_taxonomies);
	}


	/**
	 *
	 */
	private function initSelectableTaxonomies() {
		$res = array();
		foreach (self::dic()->tree()->getPathFull((int)$this->getILIASParentId()) as $node) {
			if ($node["ref_id"] != (int)$this->getILIASParentId()) {
				if ($node["type"] == "cat") {
					if (ilContainer::_lookupContainerSetting($node["obj_id"], ilObjectServiceSettingsGUI::TAXONOMIES, false)) {
						$tax_ids = ilObjTaxonomy::getUsageOfObject($node["obj_id"]);
						if (sizeof($tax_ids)) {
							$res = array_merge($res, $tax_ids);
						}
					}
				}
			}
		}
		foreach ($res as $re) {
			$this->selectable_taxonomies[$re] = ilObject2::_lookupTitle($re);
		}
	}
}
