<?php

namespace SRAG\Plugins\Hub2\Exception;

use SRAG\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomyNodeNotFoundException
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyNodeNotFoundException extends HubException {

	/**
	 * @var \SRAG\Plugins\Hub2\Taxonomy\Node\INode
	 */
	protected $node;


	/**
	 * TaxonomyNodeNotFoundException constructor.
	 *
	 * @param \SRAG\Plugins\Hub2\Taxonomy\Node\INode $node
	 */
	public function __construct(INode $node) {
		parent::__construct("ILIAS Taxonomy Node not found for: {$node->getTitle()}");
		$this->node = $node;
	}


	/**
	 * @return \SRAG\Plugins\Hub2\Taxonomy\Node\INode
	 */
	public function getNode(): INode {
		return $this->node;
	}
}