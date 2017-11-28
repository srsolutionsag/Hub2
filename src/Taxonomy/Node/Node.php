<?php

namespace SRAG\Plugins\Hub2\Taxonomy\Node;

/**
 * Class Node
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class Node implements INode {

	/**
	 * @var string
	 */
	protected $title = '';


	/**
	 * Node constructor.
	 *
	 * @param string $title
	 */
	public function __construct(string $title) { $this->title = $title; }


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle(string $title) {
		$this->title = $title;
	}
}
