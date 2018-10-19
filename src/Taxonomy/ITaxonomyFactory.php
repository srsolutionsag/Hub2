<?php

namespace srag\Plugins\Hub2\Taxonomy;

use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class ITaxonomyFactory
 *
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyFactory {

	/**
	 * @param string $title
	 *
	 * @return ITaxonomy
	 */
	public function select(string $title): ITaxonomy;


	/**
	 * @param string $title
	 *
	 * @return ITaxonomy
	 */
	public function create(string $title): ITaxonomy;


	/**
	 * @param string $node_title
	 *
	 * @return INode
	 */
	public function node(string $node_title): INode;
}
