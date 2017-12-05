<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Node;

/**
 * Interface INode
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface INode {

	/**
	 * @param string $title
	 */
	public function setTitle(string $title);


	/**
	 * @return string
	 */
	public function getTitle(): string;

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
	public function attach(INode $node): INode;
}
