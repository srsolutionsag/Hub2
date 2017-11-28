<?php

namespace SRAG\Plugins\Hub2\Taxonomy;

use SRAG\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class ITaxonomyFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyFactory {

	/**
	 * @param string $title
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function select(string $title): ITaxonomy;


	/**
	 * @param string $title
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function create(string $title): ITaxonomy;


	/**
	 * @param string $node_title
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\Node\INode
	 */
	public function node(string $node_title): INode;
}
