<?php

namespace srag\Plugins\Hub2\Taxonomy\Node;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class Node
 *
 * @package srag\Plugins\Hub2\Taxonomy\Node
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Node implements INode {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var string
	 */
	protected $title = '';
	/**
	 * @var INode[]
	 */
	protected $nodes = [];


	/**
	 * Node constructor
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


	/**
	 * @inheritDoc
	 */
	public function getNodes(): array {
		return $this->nodes;
	}


	/**
	 * @inheritDoc
	 */
	public function getNodeTitlesAsArray(): array {
		$titles = [];
		foreach ($this->nodes as $node) {
			$titles[] = $node->getTitle();
		}

		return $titles;
	}


	/**
	 * @inheritDoc
	 */
	public function attach(INode $node): INode {
		$this->nodes[] = $node;

		return $this;
	}
}
