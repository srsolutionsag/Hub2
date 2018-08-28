<?php

namespace SRAG\Plugins\Hub2\Taxonomy;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Taxonomy\Node\INode;
use SRAG\Plugins\Hub2\Taxonomy\Node\Node;

/**
 * Class TaxonomyFactory
 *
 * @package SRAG\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyFactory implements ITaxonomyFactory {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritDoc
	 */
	public function select(string $title): ITaxonomy {
		return new Taxonomy($title, ITaxonomy::MODE_SELECT);
	}


	/**
	 * @inheritDoc
	 */
	public function create(string $title): ITaxonomy {
		return new Taxonomy($title, ITaxonomy::MODE_CREATE);
	}


	/**
	 * @inheritDoc
	 */
	public function node(string $node_title): INode {
		return new Node($node_title);
	}
}
