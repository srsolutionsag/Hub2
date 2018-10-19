<?php

namespace srag\Plugins\Hub2\Taxonomy\Node;

/**
 * Interface INode
 *
 * @package srag\Plugins\Hub2\Taxonomy\Node
 * @author  Fabian Schmid <fs@studer-raimann.ch>
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
	 * @param INode $node
	 *
	 * @return INode
	 */
	public function attach(INode $node): INode;
}
