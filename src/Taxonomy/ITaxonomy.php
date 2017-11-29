<?php

namespace SRAG\Plugins\Hub2\Taxonomy;

use SRAG\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Interface ITaxonomy
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomy {

	const MODE_SELECT = 1;
	const MODE_CREATE = 2;


	/**
	 * @return string
	 */
	public function getTitle(): string;


	/**
	 * @return int ITaxonomy::MODE_SELECT or ITaxonomy::MODE_CREATE
	 */
	public function getMode(): int;


	/**
	 * @return INode[]
	 */
	public function getNodes(): array;


	/**
	 * @return string[]
	 */
	public function getNodeTitlesAsArray(): array;


	/**
	 * @param \SRAG\Plugins\Hub2\Taxonomy\Node\INode $node
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function attach(INode $node): ITaxonomy;


	/**
	 * @return string
	 */
	public function __toString(): string;
}
