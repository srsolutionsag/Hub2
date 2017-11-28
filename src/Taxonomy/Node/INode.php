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
}
