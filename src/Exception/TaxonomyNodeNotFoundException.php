<?php

namespace SRAG\Plugins\Hub2\Exception;

use SRAG\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomyNodeNotFoundException
 *
 * @package SRAG\Plugins\Hub2\Exception
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyNodeNotFoundException extends HubException {

	/**
	 * @var INode
	 */
	protected $node;


	/**
	 * TaxonomyNodeNotFoundException constructor.
	 *
	 * @param INode $node
	 */
	public function __construct(INode $node) {
		parent::__construct("ILIAS Taxonomy Node not found for: {$node->getTitle()}");
		$this->node = $node;
	}


	/**
	 * @return INode
	 */
	public function getNode(): INode {
		return $this->node;
	}
}
